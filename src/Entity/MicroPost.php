<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MicroPostRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class MicroPost
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=280)
     * @Assert\NotBlank()
     * @Assert\Length(min=10, minMessage="Nem elég hosszú")
     */
    private $text;

    /**
     * @ORM\Column(type="datetime")
     */
    private $time;

    // Relations are defined in annotations, this is how the post will link to a user who posted it
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="posts")
     * @ORM\JoinColumn()
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="postsLiked")
     * @ORM\JoinTable(name="post_likes",
     * joinColumns={
     *     @ORM\JoinColumn(name="post_id", referencedColumnName="id")
     * },
     * inverseJoinColumns={
     *     @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $likedBy;

    /**
     * @return Collection
     */
    public function getLikedBy()
    {
        return $this->likedBy;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User|object $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getText(): ?string
    {
        return $this->text;
    }

    public function getTime(): \DateTime
    {
        return $this->time;
    }

    /**
     * @param mixed $text
     */
    public function setText($text): void
    {
        $this->text = $text;
    }

    /**
     * @param mixed $time
     */
    public function setTime($time): void
    {
        $this->time = $time;
    }

    /**
     * @ORM\PrePersist()
     * @throws \Exception
     */
    public function setTimeOnPersist(): void
    {
        $dateTime = new \DateTime();
        $this->time = $dateTime;
    }

    /**
     * @param User $user
     */
    public function like(User $user)
    {
        if($this->getLikedBy()->contains($user)) {
            return;
        }

        $this->getLikedBy()->add($user);
    }

    public function __construct()
    {
        $this->likedBy = new ArrayCollection();
    }
}
