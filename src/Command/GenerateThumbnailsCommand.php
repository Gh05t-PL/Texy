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
	    $this->commandsDir = $parameterBag->get('kernel.project_dir') . '/src/Command/';
	    $this->textService = $textService;
    }

	protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
	        ->addArgument('bufferSize', InputArgument::OPTIONAL, "buffer size for renderer");
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
	    $i = 1;
	    $bufferSize = (int) ($input->getArgument('bufferSize') ? $input->getArgument('bufferSize') : 1);
	    foreach ($texies as $texy) {
		    $t = $this->thumbnailsDir . $texy->getShortcut();
		    $io->writeln($t);
	    	$process = new Process(['node', "{$this->commandsDir}screenshotter.js","http://192.8.1.2/texy/{$texy->getShortcut()}/html","{$t}"]);
		    $process->start();

		    $texy->setThumbnail('/images/codeThumbs/' . $texy->getShortcut() . '.png');
		    $this->textService->save($texy);
		    if ($i % $bufferSize === 0)
		    	$process->wait();
			$i++;
		    $io->writeln($process->getOutput());
	    }

	    if (!empty($texies))
	        sleep(15);

        return 0;
    }
}
