<?php

namespace App\Controller;

use App\Entity\TextEntity;
use App\Utils\EntityServices\TextService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BasicController extends AbstractController
{
	const LIMIT_TEXIES = 25;

    /**
     * @Route("/")
     */
    public function index()
    {
        return $this->render('basic/index.html.twig', [
            'controller_name' => 'BasicController',
        ]);
    }

	/**
	 * @Route("/texy/{shortcut}")
	 */
	public function texy_display(string $shortcut)
	{
		return $this->render('basic/display_texy.html.twig', [
			'shortcut' => $shortcut,
		]);
	}

	/**
	 * @Route("/texy/{shortcut}/html")
	 */
	public function texy_display_html(string $shortcut, TextService $textService)
	{
		$textService->getOneByShortcut($shortcut);

		return $this->render('basic/display_texy_html.html.twig', [
			'shortcut' => $shortcut,
			'texy' => $textService->getOneByShortcut($shortcut)
		]);
	}

	/**
	 * @Route("list/texy/{page}")
	 */
	public function texy_display_list(int $page = 1, TextService $textService)
	{
		$texies = $textService->getTextBy(
			[
				'isPrivate' => false
			],
			['creationDate' => 'DESC'],
			self::LIMIT_TEXIES,
			($page - 1) * self::LIMIT_TEXIES
		);

		$count = (int)$this->getDoctrine()->getRepository(TextEntity::class)
			->createQueryBuilder('t')
			->select('count(t.id)')
			->where('t.isPrivate = 0')
			->getQuery()->getSingleScalarResult();

		return $this->render('basic/display_texy_list.html.twig', [
			'texies' => $texies,
			'count' => $count,
			'pages' => ceil($count / self::LIMIT_TEXIES),
			'page' => $page
		]);
	}
}
