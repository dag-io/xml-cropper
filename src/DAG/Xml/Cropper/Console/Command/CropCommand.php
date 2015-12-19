<?php
namespace DAG\Xml\Cropper\Console\Command;

use DAG\Xml\Cropper\Cropper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CropCommand.
 */
final class CropCommand extends Command
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('crop')
            ->setDefinition(
                [
                    new InputArgument(
                        'file',
                        InputArgument::REQUIRED,
                        'The file to crop'
                    ),
                    new InputArgument(
                        'path',
                        InputArgument::REQUIRED,
                        'The xpath to select the element to keep'
                    ),
                    new InputOption('save-to', null, InputOption::VALUE_REQUIRED),
                ]
            )
            ->setDescription('Crop an XML file');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $cropper = new Cropper();

        $result = $cropper->crop(
            $input->getArgument('file'),
            $input->getArgument('path')
        );

        $saveTo = $input->getOption('save-to');
        if ($saveTo) {
            if (!file_put_contents($saveTo, $result)) {
                throw new \InvalidArgumentException(sprintf('Can not write in "%s"', $saveTo));
            }
        } else {
            $output->writeln($result);
        }
    }
}
