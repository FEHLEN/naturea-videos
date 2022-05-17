<?php

namespace App\Entity;

use App\Repository\AccueilSliderRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AccueilSliderRepository::class)
 */
class AccueilSlider
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $buttonMessage;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $buttonUrl;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $imageSlider;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isDisplayed;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getButtonMessage(): ?string
    {
        return $this->buttonMessage;
    }

    public function setButtonMessage(string $buttonMessage): self
    {
        $this->buttonMessage = $buttonMessage;

        return $this;
    }

    public function getButtonUrl(): ?string
    {
        return $this->buttonUrl;
    }

    public function setButtonUrl(string $buttonUrl): self
    {
        $this->buttonUrl = $buttonUrl;

        return $this;
    }

    public function getImageSlider(): ?string
    {
        return $this->imageSlider;
    }

    public function setImageSlider(string $imageSlider): self
    {
        $this->imageSlider = $imageSlider;

        return $this;
    }

    public function getIsDisplayed(): ?bool
    {
        return $this->isDisplayed;
    }

    public function setIsDisplayed(?bool $isDisplayed): self
    {
        $this->isDisplayed = $isDisplayed;

        return $this;
    }
}
