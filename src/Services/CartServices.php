<?php
namespace App\Services;

use App\Repository\ProductRepository;
use  Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartServices{
    private $session;
    private $repoProduct;
    private $tva = 0.2;

    public function __construct(SessionInterface $session, ProductRepository $repoProduct){
        $this->session = $session;
        $this->repoProduct = $repoProduct;
    }

    public function addToCart($id){
        $cart = $this->getCart();
        if(isset($cart[$id])){
            //produit est déjà dans le panier
            $cart[$id]++;
        }else{
            // le produit n'est pas dans le panier
            $cart[$id] = 1;
        }
        $this->updateCart($cart);
    }

    public function deleteFromCart($id){
        $cart = $this->getCart();
        //produit est dans le panier
        if(isset($cart[$id])){
            //quantity de produit supérieur à 1
            if($cart[$id] > 1){
                $cart[$id]--;
            }else{
                unset($cart[$id]);
            }
            $this->updateCart($cart);
        }
    }

    public function deleteAllToCart($id){
        $cart = $this->getCart();
        //produit est dans le panier
        if(isset($cart[$id])){
            //tous supprimer
            unset($cart[$id]);
            $this->updateCart($cart);
        }
    }

    public function deleteCart(){
        $this->updateCart([]);
    }

    public function updateCart($cart){
        $this->session->set('cart', $cart);
        $this->session->set('cartData', $this->getFullCart());
    }

    public function getCart(){
        return $this->session->get('cart',[]);
    }

    public function getFullCart(){
        $cart = $this->getCart();
        $fullCart = [];
        $quantity_cart = 0;
        $subTotal = 0;
        
        foreach ($cart as $id => $quantity) {
            $product = $this->repoProduct->find($id);
            if($product){
                //produit récupéré avec succès première clé products objet fullcart 
                $fullCart['products'][] = [
                    "quantity" => $quantity,
                    "product" => $product
                ];
                $quantity_cart += $quantity;
                $subTotal += $quantity * $product->getPrice()/100;
            }else{
                //identifiant incorrect
                $this->deleteFromCart($id);
            }
        }
        //deuxième clé data dans l'objet fullcart
        $fullCart['data'] = [
            "quantity_cart" => $quantity_cart,
            "subTotalHT" => $subTotal,
            "Taxe" => round($subTotal*$this->tva,2),
            "subTotalTTC" => round(($subTotal + ($subTotal*$this->tva)),2)
        ];
        return $fullCart;
    }

}

