<?php

namespace App\Command;

use App\Utils\EntityServices\TextService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Process\Process;

class GenerateThumbnailsCommand extends Command
{
    protected static $defaultName = 'generate:thumbnails';
	protected $textService;
	protected $thumbnailsDir;

    public function __construct(string $name = null, TextService $textService, ParameterBagInterface $parameterBag)
    {
	    parent::__construct($name);
	    $this->thumbnailsDir = $parameterBag->get('kernel.project_dir') . '/public/images/codeThumbs/';
	    $this->textService = $textService;
    }

	protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->writeln($this->thumbnailsDir);
	    $texies = $this->textService->getTextBy([
	    	'thumbnail' => null
	    ]);

	    //$texies = ['sdGLJDNH','ibLpz0Zw','Keu60jM3'];
	    foreach ($texies as $texy) {
		    $t = $this->thumbnailsDir . $texy->getShortcut();
		    $io->writeln($t);
	    	$process = new Process(['cutycapt', "--url=http://192.8.1.2/texy/{$texy->getShortcut()}/html","--out={$t}.png", '--delay=1500', '--min-height=1']);
		    $process->start();

		    $texy->setThumbnail('/images/codeThumbs/' . $texy->getShortcut() . '.png');
		    $this->textService->save($texy);

		    $io->writeln($process->getOutput());
	    }

	    if (!empty($texies))
	        sleep(15);
        return 0;
    }
}
