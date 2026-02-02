<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $slug = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $subname = null;

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[ORM\Column(length: 5)]
    private ?string $postcode = null;

    #[ORM\Column(length: 255)]
    private ?string $city = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $status = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $small_image = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'posts')]
    private Collection $category;

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: PostComment::class, orphanRemoval: true)]
    private Collection $comments;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $web = null;

    public function __construct()
    {
        $this->category = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getSubname(): ?string
    {
        return $this->subname;
    }

    public function setSubname(?string $subname): static
    {
        $this->subname = $subname;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCategory(): Collection
    {
        return $this->category;
    }

    public function setCategory(Collection $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getSmallImage(): ?string
    {
        return $this->small_image;
    }

    public function setSmallImage(?string $small_image): static
    {
        $this->small_image = $small_image;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function addCategory(Category $category): static
    {
        if (!$this->category->contains($category)) {
            $this->category->add($category);
        }

        return $this;
    }

    public function removeCategory(Category $category): static
    {
        $this->category->removeElement($category);

        return $this;
    }

    /**
     * @return Collection<int, PostComment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(PostComment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setPost($this);
        }

        return $this;
    }

    public function removeComment(PostComment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            if ($comment->getPost() === $this) {
                $comment->setPost(null);
            }
        }

        return $this;
    }

    public function getPostcode(): ?string
    {
        return $this->postcode;
    }

    public function setPostcode(string $postcode): static
    {
        $this->postcode = $postcode;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getWeb(): ?string
    {
        return $this->web;
    }

    public function setWeb(?string $web): static
    {
        $this->web = $web;

        return $this;
    }

    public function getWebsiteName(): ?string
    {
        if (!$this->web) {
            return null;
        }

        $parsedUrl = parse_url($this->web);
        if (!$parsedUrl || !isset($parsedUrl['host'])) {
            return null;
        }

        return $parsedUrl['host'];
    }

    public function getPhoneLink(): ?string
    {
        $phoneNumber = $this->getPhone();

        if (!$phoneNumber) {
            return null;
        }

        $phoneNumber = preg_replace('/\D/', '', $phoneNumber);

        if (substr($phoneNumber, 0, 2) == "01") {
            $phoneNumber = "+49" . substr($phoneNumber, 1);
            return $phoneNumber;
        }

        if (substr($phoneNumber, 0, 2) == "49") {
            $phoneNumber = "+49" . substr($phoneNumber, 2);
            return $phoneNumber;
        }

        if (substr($phoneNumber, 0, 3) == "+49") {
            $phoneNumber = "+49" . substr($phoneNumber, 3);
            return $phoneNumber;
        }

        if (substr($phoneNumber, 0, 1) == "0") {
            $phoneNumber = "+49" . substr($phoneNumber, 1);
            return $phoneNumber;
        }

        return null;
    }

    public function getFormatedPhone(): ?string
    {
        $phoneNumber = $this->getPhone();

        if (!$phoneNumber) {
            return null;
        }
        
        $phoneNumber = preg_replace('/\D/', '', $phoneNumber);

        if (substr($phoneNumber, 0, 2) == "01") {
            $areaCode = substr($phoneNumber, 2, 3);
            $prefix = substr($phoneNumber, 5, 3);
            $lineNumber = substr($phoneNumber, 8);
            return "+49 ($areaCode) $prefix-$lineNumber";
        }

        if (substr($phoneNumber, 0, 2) == "49") {
            $areaCode = substr($phoneNumber, 2, 3);
            $prefix = substr($phoneNumber, 5, 3);
            $lineNumber = substr($phoneNumber, 8);
            return "+49 ($areaCode) $prefix-$lineNumber";
        }

        if (substr($phoneNumber, 0, 3) == "+49") {
            $areaCode = substr($phoneNumber, 3, 3);
            $prefix = substr($phoneNumber, 6, 3);
            $lineNumber = substr($phoneNumber, 9);
            return "+49 ($areaCode) $prefix-$lineNumber";
        }

        if (substr($phoneNumber, 0, 1) == "0") {
            $areaCode = substr($phoneNumber, 1, 4);
            $prefix = substr($phoneNumber, 5, 3);
            $lineNumber = substr($phoneNumber, 8);
            return "+49 ($areaCode) $prefix-$lineNumber";
        }

        return $this->getPhone();
    }

    public function getHeadline(): ?string
    {
        if ($this->getSubname()) {
            return $this->getSubname();
        }

        if ($this->getCategory()->count() > 0 && $this->getCity()) {
            return $this->getCategory()->first()->getName() . " in " . $this->getCity();
        }
    }
}
