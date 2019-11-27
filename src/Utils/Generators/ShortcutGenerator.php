<?php


namespace App\Utils\Generators;


use Doctrine\ORM\EntityManagerInterface;

class ShortcutGenerator implements IShortcutGenerator
{
	const SHORTCUT_LENGTH = 8;

	private $em;

	public function __construct(EntityManagerInterface $em)
	{
		$this->em = $em;
	}

	public function generate()
	{
		$dictionary = array_merge(range('a', 'z') , range('0', '9') , range('A', 'Z'));
		shuffle($dictionary);

		$dictLength = count($dictionary)-1;

		$string = '';
		for ($i = 0; $i < self::SHORTCUT_LENGTH; $i++) {
			$string .= $dictionary[random_int(0, $dictLength)];
		}


		return $string;
	}
}