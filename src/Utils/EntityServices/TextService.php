<?php


namespace App\Utils\EntityServices;


use App\Entity\TextEntity;
use Doctrine\ORM\EntityManagerInterface;

class TextService
{
	private $em;
	private $textRepository;

	public function __construct(EntityManagerInterface $em)
	{
		$this->em = $em;
		$this->textRepository = $em->getRepository(TextEntity::class);
	}

	public function getOneTextBy(array $criteria): ?TextEntity
	{
		return $this->textRepository->findOneBy(array_merge(
			$criteria,
			[

			]
		));
	}

	public function getOneByShortcut(string $shortcut): ?TextEntity
	{
		return $this->textRepository->findOneBy([
			'shortcut' => $shortcut
		]);
	}

	public function getTextBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
	{
		return $this->textRepository->findBy(
			array_merge(
				$criteria,
				[

				]
			),
			$orderBy,
			$limit,
			$offset
		);
	}

	public function remove(TextEntity $text)
	{
		$this->em->remove($text);
		$this->em->flush($text);
	}

	public function save(TextEntity $text)
	{
		!$this->em->getUnitOfWork()->isEntityScheduled($text) ? $this->em->persist($text) : null;

		$this->em->flush($text);
	}
}