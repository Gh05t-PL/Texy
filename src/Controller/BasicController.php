<?php

namespace App\Controller;

use App\Utils\EntityServices\TextService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BasicController extends AbstractController
{
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
	 * @Route("/texy/list/{page}")
	 */
	public function texy_display_list(int $page = 1, TextService $textService)
	{
		$texies = $textService->getTextBy([]);

		return $this->render('basic/display_texy_list.html.twig', [
			'texies' => $texies,
		]);
	}
}
