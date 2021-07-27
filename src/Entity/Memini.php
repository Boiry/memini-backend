<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use App\Repository\MeminiRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;

/**
 * @ORM\Entity(repositoryClass=MeminiRepository::class)
 */
class Memini
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="boolean")
     */
    private $public;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $picture;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tag;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $sendAt;

    /**$
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="memini", cascade={"remove"})
     * @ORM\JoinColumn(name="comments_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $comments;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="meminis")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isSent;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->isSent = false;
        $this->comments = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->isSent = false;
    }

    public function __toString(): ?string
    {
        return $this->id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getPublic(): ?bool
    {
        return $this->public;
    }

    public function setPublic(bool $public): self
    {
        $this->public = $public;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getTag(): ?string
    {
        return $this->tag;
    }

    public function setTag(?string $tag): self
    {
        $this->tag = $tag;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getSendAt(): ?\DateTimeInterface
    {
        return $this->sendAt;
    }

    public function setSendAt(\DateTimeInterface $sendAt): self
    {
        $this->sendAt = $sendAt;

        return $this;
    }

    public function getCommentsList(): ?array
    {
        if ($this->comments) {
            $commentsJson = [];
            foreach ($this->comments as $comment) {
                $commentsJson[] = [
                    'id' => $comment['id'],
                    'name' => $comment['name'],
                    'content' => $comment['content'],
                    'createdAt' => $comment['createdAt'],
                ];
            }
            return $commentsJson;
        } else {
            return null;
        }
    }

    public function setComments($comments)
    {
        $this->comments = $comments;
    }

    /**
     * @Ignore()
     * @return Collection|Comment[]
     */
    public function getComments(): ?Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setMemini($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getMemini() === $this) {
                $comment->setMemini(null);
            }
        }

        return $this;
    }

    public function getUsername(): array
    {
        $user = [
            'id' => $this->user->getId(),
            'name' => $this->user->getName(),
            'avatar' => $this->user->getAvatar()
        ];
        return $user;
    }

    public function getUserId()
    {
        return $this->user->getId();
    }

    /**
     * @Ignore()
     */

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getIsSent(): ?bool
    {
        return $this->isSent;
    }

    public function setIsSent(bool $isSent): self
    {
        $this->isSent = $isSent;

        return $this;
    }
}
