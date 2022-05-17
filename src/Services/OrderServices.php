<?php
namespace App\Services;

use App\Entity\Cart;
use App\Entity\Orders;
use App\Entity\Product;
use App\Entity\CartDetails;
use App\Entity\OrderDetails;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;

class OrderServices{
    private $manager;
    private $repoProduct;

    public function __construct(EntityManagerInterface $manager, ProductRepository $repoProduct)
    {
        $this->manager = $manager;
        $this->repoProduct = $repoProduct;
 
    }

    public function createOrder($cart)
    {
       
        $order = new Orders();//remplissage  table orders
        $order->setReference($cart->getReference())
        ->setFullname($cart->getFullName())
            ->setTransportName($cart->getTransportName())
            ->setTransportPrice($cart->getTransportPrice())
            ->setLivraisonAdresse($cart->getLivraisonAdresse())
            ->setQuantity($cart->getQuantity())
            ->setSubTotalHT($cart->getSubTotalHT())
            ->setTaxe($cart->getTaxe())
            ->setSubTotalTTC($cart->getSubTotalTTC())
            ->setUser($cart->getUser());
        $this->manager->persist($order);
        
        $products = $cart->getCartDetails()->getValues();

        foreach ($products as $cart_product) {
            $orderDetails = new OrderDetails();//remplissage table orderDetails
            $orderDetails->setProductName($cart_product->getProductName())
                         ->setProductPrice($cart_product->getProductPrice())
                         ->setProductQuantity($cart_product->getProductQuantity())
                         ->setSubtotalHt($cart_product->getSubTotalHt())
                         ->setTaxe($cart_product->getTaxe())
                         ->setSubTotalTtc($cart_product->getSubTotalTtc())
                         ->setLienOrder($order);
                         
            $this->manager->persist($orderDetails);
        }

        $this->manager->flush();

        return $order;
    }
    public function saveCart($data, $user)
    {
        /*$data est un tableau
           [
            'products' => ['quantity', 'product'],//tous les produits du panier
            'data' => [],//sous-total, taxe, totalTTC
            'checkout' => [
                'address' => objet,
                'transporteurs' => objet,
                'informations' => sdfsfn
            ]
        ]*/
        $cart = new Cart();//remplissage de la table cart
        $reference = $this->generateUuid();
        $address = $data['checkout']['address'];
        $transport = $data['checkout']['transporteurs'];
        $informations = $data['checkout']['informations'];
        

        $cart->setReference($reference)
             ->setFullname($address->getFullName())
             ->setTransportName($transport->getNameTransport())
             ->setTransportPrice($transport->getPrice())
             ->setLivraisonAdresse($address)
             ->setCreatedAt(new \DateTime())
             ->setQuantity($data['data']['quantity_cart'])//voir dans CartServices.php
             ->setSubTotalHT($data['data']['subTotalHT'])
             ->setTaxe($data['data']['Taxe'])
             ->setSubTotalTTC($data['data']['subTotalTTC']+$transport->getPrice()/100)
             ->setUser($user);
              
        $this->manager->persist($cart);
        
        //creation de l'objet cart details
        $cart_details_array = [];
        //dans session deux clés voir dans CartServices : "quantity" => $quantity, "product" => $product
        foreach ($data['products'] as $products) {
            $cartDetails = new CartDetails(); //remplissage de la table cart-details
            $subtotal = $products['quantity'] * $products['product']->getPrice()/100;
            $cartDetails->setCart($cart)
            ->setProductName($products['product']->getNameProduct())
            ->setProductPrice($products['product']->getPrice()/100)
            ->setProductQuantity($products['quantity'])
            ->setSubtotalHt($subtotal)
            ->setTaxe(round($subtotal*0.2),2)
            ->setSubtotalTtc(round($subtotal*1.2),2);
            
        $this->manager->persist($cartDetails);
        $cart_details_array[] = $cartDetails;
        }
        $this->manager->flush();

        return $reference;
    }
    public function getLineItems($cart){
        $cartDetails = $cart->getCartDetails();
        $transportName = $cart->getTransportName();
        $transportPrice = $cart->getTransportPrice();
        $taxe = $cart->getTaxe();
        $line_items = [];
        foreach ($cartDetails as $details) {
            //$produit = $this->repoProduct->findOneByName($details->getProductName());//ne fonctionne pas
            
            $line_items[] = [
                'price_data' => [
                  'currency' => 'eur',
                  'unit_amount' => $details->getProductPrice()*100,
                  'product_data' => [
                    'name' => $details->getProductName(),
                  ],
                ],
                'quantity' =>  $details->getProductQuantity(),
            ];
        }
        //transport
        $line_items[] = [
            'price_data' => [
              'currency' => 'eur',
              'unit_amount' => $transportPrice,
              'product_data' => [
                'name' => 'Transport ( '.$transportName.' )',
              ],
            ],
            'quantity' =>  1,
        ];
        // Taxe
        $line_items[] = [
            'price_data' => [
              'currency' => 'eur',
              'unit_amount' => $taxe,
              'product_data' => [
                'name' => 'TVA (20%)',
              ],
            ],
            'quantity' =>  1,
        ];
        return $line_items;
    }

    public function generateUuid()
    {
        // Initialise le générateur de nombres aléatoires Mersenne Twister
        mt_srand((double)microtime()*100000);

        //strtoupper : Renvoie une chaîne en majuscules
        //uniqid : Génère un identifiant unique
        $charid = strtoupper(md5(uniqid(rand(), true)));

        //Générer une chaîne d'un octet à partir d'un nombre
        $hyphen = chr(45);

        //substr : Retourne un segment de chaîne
        $uuid = ""
        .substr($charid, 0, 8).$hyphen
        .substr($charid, 8, 4).$hyphen
        .substr($charid, 12, 4).$hyphen
        .substr($charid, 16, 4).$hyphen
        .substr($charid, 20, 12);
        
        return $uuid;
    }
}