<?php

namespace App\Controller;

use App\Entity\TextEntity;
use App\Utils\APIResponseHelper;
use App\Utils\EntityServices\TextService;
use App\Utils\Generators\ShortcutGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Json;
use Symfony\Component\Validator\Validation;

class ApiTextController extends AbstractController
{
    /**
     * @Route("/api/v1/text/{shortcut}", methods={"GET"})
     */
    public function getText(string $shortcut, TextService $textService)
    {
    	$textEntity = $textService->getOneTextBy([
    		'shortcut' => $shortcut
	    ]);

    	if ( $textEntity === null ) {
    		return $this->json(
			    APIResponseHelper::getResponse(
			    	false,
				    null,
				    [
				    	[
				    		'code' => 'texy.notFound',
					        'message' => 'Texy not found'
					    ]
				    ]
			    )
		    );
	    }
    	if ( new \DateTime() >= $textEntity->getExpirationDate() ) {
		    return $this->json(
			    APIResponseHelper::getResponse(
				    false,
				    null,
				    [
					    [
						    'code' => 'texy.expired',
						    'message' => 'Texy expired'
					    ]
				    ]
			    ),
			    Response::HTTP_GONE
		    );
	    }

		return $this->json(
			APIResponseHelper::getResponse(
				true,
				$this->normalizeTextEntity($textEntity)
			)
		);
    }

	/**
	 * @Route("/api/v1/text", methods={"POST"})
	 */
	public function createText(Request $request, TextService $textService, ShortcutGenerator $generator)
	{
		$validator = Validation::createValidator();
		if ( $validator->validate($request->getContent(), [new Json()])->count() > 0 )
			return new JsonResponse(
				APIResponseHelper::getResponse(false, null, [
					[
						'code' => 'request.invalidJson',
						'message' => 'Invalid JSON.'
					]
				]),
				400
			);
		$requestData = json_decode($request->getContent());


		$date = new \DateTime();
		$textEntity = new TextEntity();

		$textEntity->setCreationDate($date)
			->setContent($requestData->content)
			->setExpirationDate((clone $date)->add(new \DateInterval($requestData->expirationInterval)))
			->setShortcut($generator->generate())
			->setSyntaxLanguage($requestData->language)
			->setOpens(100)
			->setTitle($requestData->name);

		$textService->save($textEntity);

		return $this->json(
			APIResponseHelper::getResponse(
				true,
				[
					"id" => $textEntity->getId(),
					"shortcut" => $textEntity->getShortcut()
				]
			),
			Response::HTTP_CREATED
		);
    }

	public function normalizeTextEntity(TextEntity $textEntity)
	{
		return [
			'id' => $textEntity->getId(),
			'expirationDate' => $textEntity->getExpirationDate(),
			'content' => $textEntity->getContent(),
			'shortcut' => $textEntity->getShortcut(),
			'creationDate' => $textEntity->getCreationDate(),
			'limitOpens' => $textEntity->getLimitOpens(),
			'opens' => $textEntity->getOpens(),
			'language' => $textEntity->getSyntaxLanguage(),
			'title' => $textEntity->getTitle(),
		];
    }
}
