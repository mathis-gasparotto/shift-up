<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Helper\GlobalHelper;
use App\Helper\ProjectHelper;
use App\Model\TracingAwareInterface;
use App\Model\Traits\TracingAwareTrait;
use App\Repository\ProjectRepository;
use App\StateProcessor\Project\ProjectPostDataPersister;
use App\StateProviders\ProjectByTeamCollectionDataProvider;
use App\StateProviders\ProjectMeCollectionDataProvider;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 */
#[ORM\Entity(repositoryClass: ProjectRepository::class)]
#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/projects',
            openapiContext: [
                'requestBody' => [
                    'content' => [
                        'application/ld+json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'team' => [
                                        'type' => 'string',
                                        'example' =>'/teams/{id}'
                                    ],
                                    'name' => [
                                        'type' => 'string'
                                    ],
                                    'description' => [
                                        'type' => 'string'
                                    ],
                                    'sellingObject' => [
                                        'type' => 'string'
                                    ]
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            normalizationContext: [
                'openapi_definition_name' => 'PostCollection'
            ],
            security: 'is_granted("' . GlobalHelper::ROLE_USER . '")',
            processor: ProjectPostDataPersister::class
        ),
        new GetCollection(
            uriTemplate: '/projects',
            normalizationContext: [
                'openapi_definition_name' => 'GetCollection',
                'groups' => [
                    'project:read'
                ]
            ],
            security: 'is_granted("' . GlobalHelper::ROLE_USER . '")',
            provider: ProjectMeCollectionDataProvider::class
        ),
        new Get(
            uriTemplate: '/projects/{id}',
            requirements: [
                'id' => '^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$'
            ],
            normalizationContext: [
                'openapi_definition_name' => 'GetItem',
                'groups' => [
                    'project:read',
                    'project:item:read'
                ]
            ],
            security: '
                is_granted("' . GlobalHelper::ROLE_ADMIN . '") or
                (is_granted("' . GlobalHelper::ROLE_USER . '") and user.isInTeam(object.getTeam())) or
                (is_granted("' . GlobalHelper::ROLE_USER . '") and object.getTeam().getManager() === user)
            '
        ),
        new Put(
            uriTemplate: '/projects/{id}',
            requirements: [
                'id' => '^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$'
            ],
            normalizationContext: [
                'openapi_definition_name' => 'PutItem'
            ],
            securityPostDenormalize: '
                is_granted("' . GlobalHelper::ROLE_ADMIN . '") or
                (is_granted("' . GlobalHelper::ROLE_USER . '") and user.isInTeam(object.getTeam())) or
                (is_granted("' . GlobalHelper::ROLE_USER . '") and object.getTeam().getManager() === user)
            '
        ),
        new Delete(
            normalizationContext: [
                'openapi_definition_name' => 'DeleteItem'
            ],
            security: '
                is_granted("' . GlobalHelper::ROLE_ADMIN . '") or
                (is_granted("' . GlobalHelper::ROLE_USER . '") and user.isInTeam(object.getTeam())) or
                (is_granted("' . GlobalHelper::ROLE_USER . '") and object.getTeam().getManager() === user)
            '
        )
    ]
)]
#[ApiResource(
    uriTemplate: '/teams/{id}/projects',
    operations: [new GetCollection()],
    uriVariables: [
        'id' => new Link(
            toProperty: 'team',
            fromClass: Team::class
        )
    ],
    normalizationContext: [
        'groups' => [
            'project:read'
        ]
    ],
    security: 'is_granted("' . GlobalHelper::ROLE_USER . '")',
    provider: ProjectByTeamCollectionDataProvider::class
)]
class Project implements TracingAwareInterface
{
    use TracingAwareTrait;

    /**
     * @var Uuid|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator('doctrine.uuid_generator')]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[Groups(['project:read', 'team:read'])]
    private ?Uuid $id = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    #[
        Assert\NotBlank,
        Groups(['project:read', 'project:write', 'team:read'])
    ]
    private ?string $name = null;

    /**
     * @var string|null
     */
    #[ORM\Column(type: Types::TEXT)]
    #[
        Assert\NotBlank,
        Groups(['project:read', 'project:write'])
    ]
    private ?string $description = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    #[
        Assert\Choice(
            choices: ProjectHelper::STATUS,
            message: 'Invalid status, valid status are: {{ choices }}'
        ),
        Groups(['project:read'])
    ]
    private ?string $status = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['project:read', 'project:write'])]
    private ?string $sellingObject = null;

    /**
     * @var Team|null
     */
    #[ORM\ManyToOne(inversedBy: 'projects')]
    #[ORM\JoinColumn(nullable: false)]
    #[
        Assert\NotBlank,
        Groups(['project:read', 'project:write'])
    ]
    private ?Team $team = null;

    /**
     * @var Collection|ArrayCollection
     */
    #[ORM\OneToMany(mappedBy: 'project', targetEntity: SWOT::class, orphanRemoval: true)]
    #[
        Groups(['project:item:read'])
    ]
    private Collection $SWOTs;

    /**
     * @var Collection|ArrayCollection
     */
    #[ORM\OneToMany(mappedBy: 'project', targetEntity: BusinessModelCanvas::class, orphanRemoval: true)]
    #[
        Groups(['project:item:read'])
    ]
    private Collection $businessModelCanvases;

    /**
     * @var Collection|ArrayCollection
     */
    #[ORM\OneToMany(mappedBy: 'project', targetEntity: SMART::class, orphanRemoval: true)]
    #[
        Groups(['project:item:read'])
    ]
    private Collection $SMARTs;

    /**
     * @var Collection|ArrayCollection
     */
    #[ORM\OneToMany(mappedBy: 'project', targetEntity: BuyerPersona::class, orphanRemoval: true)]
    #[
        Groups(['project:item:read'])
    ]
    private Collection $buyerPersonas;

    /**
     * @var Collection|ArrayCollection
     */
    #[ORM\OneToMany(mappedBy: 'project', targetEntity: PESTEL::class, orphanRemoval: true)]
    #[
        Groups(['project:item:read'])
    ]
    private Collection $PESTELs;

    /**
     * @var Collection|ArrayCollection
     */
    #[ORM\OneToMany(mappedBy: 'project', targetEntity: MarketingMix5::class, orphanRemoval: true)]
    #[
        Groups(['project:item:read'])
    ]
    private Collection $marketingMix5s;

    /**
     * @var Collection|ArrayCollection
     */
    #[ORM\OneToMany(mappedBy: 'project', targetEntity: STP::class, orphanRemoval: true)]
    #[
        Groups(['project:item:read'])
    ]
    private Collection $STPs;

    /**
     * @var Collection|ArrayCollection
     */
    #[ORM\OneToMany(mappedBy: 'project', targetEntity: MarketingMix4::class, orphanRemoval: true)]
    #[
        Groups(['project:item:read'])
    ]
    private Collection $marketingMix4s;

    /**
     * @var Collection|ArrayCollection
     */
    #[ORM\OneToMany(mappedBy: 'project', targetEntity: GoldenTriangle::class, orphanRemoval: true)]
    #[
        Groups(['project:item:read'])
    ]
    private Collection $goldenTriangles;

    /**
     * @var Collection|ArrayCollection
     */
    #[ORM\OneToMany(mappedBy: 'project', targetEntity: CompetitorAnalysis::class, orphanRemoval: true)]
    #[
        Groups(['project:item:read'])
    ]
    private Collection $competitorAnalyses;

    /**
     * @var MediaObject|null
     */
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[
        Groups(['project:read', 'project:write'])
    ]
    private ?MediaObject $picture = null;

    /**
     *
     */
    public function __construct()
    {
        $this->status = ProjectHelper::STATUS_ACTIVE;
        $this->SWOTs = new ArrayCollection();
        $this->businessModelCanvases = new ArrayCollection();
        $this->SMARTs = new ArrayCollection();
        $this->buyerPersonas = new ArrayCollection();
        $this->PESTELs = new ArrayCollection();
        $this->marketingMix5s = new ArrayCollection();
        $this->STPs = new ArrayCollection();
        $this->marketingMix4s = new ArrayCollection();
        $this->goldenTriangles = new ArrayCollection();
        $this->competitorAnalyses = new ArrayCollection();
    }

    /**
     * @return Uuid|null
     */
    public function getId(): ?Uuid
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return $this
     */
    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSellingObject(): ?string
    {
        return $this->sellingObject;
    }

    /**
     * @param string|null $sellingObject
     * @return $this
     */
    public function setSellingObject(?string $sellingObject): static
    {
        $this->sellingObject = $sellingObject;

        return $this;
    }

    /**
     * @return Team|null
     */
    public function getTeam(): ?Team
    {
        return $this->team;
    }

    /**
     * @param Team|null $team
     * @return $this
     */
    public function setTeam(?Team $team): static
    {
        $this->team = $team;

        return $this;
    }

    /**
     * @return Collection<int, SWOT>
     */
    public function getSWOTs(): Collection
    {
        return $this->SWOTs;
    }

    /**
     * @return SWOT|null
     */
    public function getLastSWOT(): SWOT|null
    {
        $data =  $this->SWOTs->last();
        return $data ?: null;
//        $array = $this->SWOTs->toArray();
//        if (count($array) === 0) {
//            return null;
//        }
//        usort($array, function ($a, $b) {
//            return $a->getCreatedAt() > $b->getCreatedAt() ? 1 : -1;
//        });
//        return $array[count($array) - 1];
    }

    /**
     * @param SWOT $SWOT
     * @return $this
     */
    public function addSWOT(SWOT $SWOT): static
    {
        if (!$this->SWOTs->contains($SWOT)) {
            $this->SWOTs->add($SWOT);
            $SWOT->setProject($this);
        }

        return $this;
    }

    /**
     * @param SWOT $SWOT
     * @return $this
     */
    public function removeSWOT(SWOT $SWOT): static
    {
        if ($this->SWOTs->removeElement($SWOT)) {
            // set the owning side to null (unless already changed)
            if ($SWOT->getProject() === $this) {
                $SWOT->setProject(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, BusinessModelCanvas>
     */
    public function getBusinessModelCanvases(): Collection
    {
        return $this->businessModelCanvases;
    }

    /**
     * @return BusinessModelCanvas|null
     */
    public function getLastBusinessModelCanvas(): BusinessModelCanvas|null
    {
        $data =  $this->businessModelCanvases->last();
        return $data ?: null;
    }

    /**
     * @param BusinessModelCanvas $businessModelCanvas
     * @return $this
     */
    public function addBusinessModelCanvas(BusinessModelCanvas $businessModelCanvas): static
    {
        if (!$this->businessModelCanvases->contains($businessModelCanvas)) {
            $this->businessModelCanvases->add($businessModelCanvas);
            $businessModelCanvas->setProject($this);
        }

        return $this;
    }

    /**
     * @param BusinessModelCanvas $businessModelCanvas
     * @return $this
     */
    public function removeBusinessModelCanvas(BusinessModelCanvas $businessModelCanvas): static
    {
        if ($this->businessModelCanvases->removeElement($businessModelCanvas)) {
            // set the owning side to null (unless already changed)
            if ($businessModelCanvas->getProject() === $this) {
                $businessModelCanvas->setProject(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SMART>
     */
    public function getSMARTs(): Collection
    {
        return $this->SMARTs;
    }

    /**
     * @return SMART|null
     */
    public function getLastSMART(): SMART|null
    {
        $data =  $this->SMARTs->last();
        return $data ?: null;
    }

    /**
     * @param SMART $SMART
     * @return $this
     */
    public function addSMART(SMART $SMART): static
    {
        if (!$this->SMARTs->contains($SMART)) {
            $this->SMARTs->add($SMART);
            $SMART->setProject($this);
        }

        return $this;
    }

    /**
     * @param SMART $SMART
     * @return $this
     */
    public function removeSMART(SMART $SMART): static
    {
        if ($this->SMARTs->removeElement($SMART)) {
            // set the owning side to null (unless already changed)
            if ($SMART->getProject() === $this) {
                $SMART->setProject(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, BuyerPersona>
     */
    public function getBuyerPersonas(): Collection
    {
        return $this->buyerPersonas;
    }

    /**
     * @return BuyerPersona|null
     */
    public function getLastBuyerPersona(): BuyerPersona|null
    {
        $data =  $this->buyerPersonas->last();
        return $data ?: null;
    }

    /**
     * @param BuyerPersona $buyerPersona
     * @return $this
     */
    public function addBuyerPersona(BuyerPersona $buyerPersona): static
    {
        if (!$this->buyerPersonas->contains($buyerPersona)) {
            $this->buyerPersonas->add($buyerPersona);
            $buyerPersona->setProject($this);
        }

        return $this;
    }

    /**
     * @param BuyerPersona $buyerPersona
     * @return $this
     */
    public function removeBuyerPersona(BuyerPersona $buyerPersona): static
    {
        if ($this->buyerPersonas->removeElement($buyerPersona)) {
            // set the owning side to null (unless already changed)
            if ($buyerPersona->getProject() === $this) {
                $buyerPersona->setProject(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PESTEL>
     */
    public function getPESTELs(): Collection
    {
        return $this->PESTELs;
    }

    /**
     * @return PESTEL|null
     */
    public function getLastPESTEL(): PESTEL|null
    {
        $data =  $this->PESTELs->last();
        return $data ?: null;
    }

    /**
     * @param PESTEL $PESTEL
     * @return $this
     */
    public function addPESTEL(PESTEL $PESTEL): static
    {
        if (!$this->PESTELs->contains($PESTEL)) {
            $this->PESTELs->add($PESTEL);
            $PESTEL->setProject($this);
        }

        return $this;
    }

    /**
     * @param PESTEL $PESTEL
     * @return $this
     */
    public function removePESTEL(PESTEL $PESTEL): static
    {
        if ($this->PESTELs->removeElement($PESTEL)) {
            // set the owning side to null (unless already changed)
            if ($PESTEL->getProject() === $this) {
                $PESTEL->setProject(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MarketingMix5>
     */
    public function getMarketingMix5s(): Collection
    {
        return $this->marketingMix5s;
    }

    /**
     * @return MarketingMix5|null
     */
    public function getLastMarketingMix5(): MarketingMix5|null
    {
        $data =  $this->marketingMix5s->last();
        return $data ?: null;
    }

    /**
     * @param MarketingMix5 $marketingMix5
     * @return $this
     */
    public function addMarketingMix5(MarketingMix5 $marketingMix5): static
    {
        if (!$this->marketingMix5s->contains($marketingMix5)) {
            $this->marketingMix5s->add($marketingMix5);
            $marketingMix5->setProject($this);
        }

        return $this;
    }

    /**
     * @param MarketingMix5 $marketingMix5
     * @return $this
     */
    public function removeMarketingMix5(MarketingMix5 $marketingMix5): static
    {
        if ($this->marketingMix5s->removeElement($marketingMix5)) {
            // set the owning side to null (unless already changed)
            if ($marketingMix5->getProject() === $this) {
                $marketingMix5->setProject(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, STP>
     */
    public function getSTPs(): Collection
    {
        return $this->STPs;
    }

    /**
     * @return STP|null
     */
    public function getLastSTP(): STP|null
    {
        $data =  $this->STPs->last();
        return $data ?: null;
    }

    /**
     * @param STP $STP
     * @return $this
     */
    public function addSTP(STP $STP): static
    {
        if (!$this->STPs->contains($STP)) {
            $this->STPs->add($STP);
            $STP->setProject($this);
        }

        return $this;
    }

    /**
     * @param STP $STP
     * @return $this
     */
    public function removeSTP(STP $STP): static
    {
        if ($this->STPs->removeElement($STP)) {
            // set the owning side to null (unless already changed)
            if ($STP->getProject() === $this) {
                $STP->setProject(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MarketingMix4>
     */
    public function getMarketingMix4s(): Collection
    {
        return $this->marketingMix4s;
    }

    /**
     * @return MarketingMix4|null
     */
    public function getLastMarketingMix4(): MarketingMix4|null
    {
        $data =  $this->marketingMix4s->last();
        return $data ?: null;
    }

    /**
     * @param MarketingMix4 $marketingMix4
     * @return $this
     */
    public function addMarketingMix4(MarketingMix4 $marketingMix4): static
    {
        if (!$this->marketingMix4s->contains($marketingMix4)) {
            $this->marketingMix4s->add($marketingMix4);
            $marketingMix4->setProject($this);
        }

        return $this;
    }

    /**
     * @param MarketingMix4 $marketingMix4
     * @return $this
     */
    public function removeMarketingMix4(MarketingMix4 $marketingMix4): static
    {
        if ($this->marketingMix4s->removeElement($marketingMix4)) {
            // set the owning side to null (unless already changed)
            if ($marketingMix4->getProject() === $this) {
                $marketingMix4->setProject(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, GoldenTriangle>
     */
    public function getGoldenTriangles(): Collection
    {
        return $this->goldenTriangles;
    }

    /**
     * @return GoldenTriangle|null
     */
    public function getLastGoldenTriangle(): GoldenTriangle|null
    {
        $data =  $this->goldenTriangles->last();
        return $data ?: null;
    }

    /**
     * @param GoldenTriangle $goldenTriangle
     * @return $this
     */
    public function addGoldenTriangle(GoldenTriangle $goldenTriangle): static
    {
        if (!$this->goldenTriangles->contains($goldenTriangle)) {
            $this->goldenTriangles->add($goldenTriangle);
            $goldenTriangle->setProject($this);
        }

        return $this;
    }

    /**
     * @param GoldenTriangle $goldenTriangle
     * @return $this
     */
    public function removeGoldenTriangle(GoldenTriangle $goldenTriangle): static
    {
        if ($this->goldenTriangles->removeElement($goldenTriangle)) {
            // set the owning side to null (unless already changed)
            if ($goldenTriangle->getProject() === $this) {
                $goldenTriangle->setProject(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CompetitorAnalysis>
     */
    public function getCompetitorAnalyses(): Collection
    {
        return $this->competitorAnalyses;
    }

    /**
     * @return CompetitorAnalysis|null
     */
    public function getLastCompetitorAnalysis(): CompetitorAnalysis|null
    {
        $data =  $this->competitorAnalyses->last();
        return $data ?: null;
    }

    /**
     * @param CompetitorAnalysis $competitorAnalysis
     * @return $this
     */
    public function addCompetitorAnalysis(CompetitorAnalysis $competitorAnalysis): static
    {
        if (!$this->competitorAnalyses->contains($competitorAnalysis)) {
            $this->competitorAnalyses->add($competitorAnalysis);
            $competitorAnalysis->setProject($this);
        }

        return $this;
    }

    /**
     * @param CompetitorAnalysis $competitorAnalysis
     * @return $this
     */
    public function removeCompetitorAnalysis(CompetitorAnalysis $competitorAnalysis): static
    {
        if ($this->competitorAnalyses->removeElement($competitorAnalysis)) {
            // set the owning side to null (unless already changed)
            if ($competitorAnalysis->getProject() === $this) {
                $competitorAnalysis->setProject(null);
            }
        }

        return $this;
    }

    /**
     * @return MediaObject|null
     */
    public function getPicture(): ?MediaObject
    {
        return $this->picture;
    }

    /**
     * @param MediaObject|null $picture
     * @return $this
     */
    public function setPicture(?MediaObject $picture): static
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    #[
        SerializedName("lastDocuments"),
        Groups(['project:read'])
    ]
    public function getLastDocuments(): ArrayCollection
    {
        $documents = new ArrayCollection();
        if ($this->getLastSWOT()) $documents->add($this->getLastSWOT());
        if ($this->getLastBusinessModelCanvas()) $documents->add($this->getLastBusinessModelCanvas());
        if ($this->getLastSMART()) $documents->add($this->getLastSMART());
        if ($this->getLastBuyerPersona()) $documents->add($this->getLastBuyerPersona());
        if ($this->getLastPESTEL()) $documents->add($this->getLastPESTEL());
        if ($this->getLastMarketingMix5()) $documents->add($this->getLastMarketingMix5());
        if ($this->getLastSTP()) $documents->add($this->getLastSTP());
        if ($this->getLastMarketingMix4()) $documents->add($this->getLastMarketingMix4());
        if ($this->getLastGoldenTriangle()) $documents->add($this->getLastGoldenTriangle());
        if ($this->getLastCompetitorAnalysis()) $documents->add($this->getLastCompetitorAnalysis());
        return $documents;
    }
}
