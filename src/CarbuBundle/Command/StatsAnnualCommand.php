<?php
namespace CarbuBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class StatsAnnualCommand extends ContainerAwareCommand
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('carbu:stats:annual')
            ->setDescription('Calc annual statistics')
            ->setDefinition(array(
                new InputArgument('year', InputArgument::REQUIRED, 'Year (YYYY)'),
            ))
            ->setHelp(<<<'EOT'
The <info>%command.full_name%</info> calculate stats for a year:

  <info>php %command.full_name% 2017</info>
EOT
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $month = $input->getArgument('year');

        $output->writeln(sprintf('Year "%s".', $month));
    }

    /**
     * {@inheritdoc}
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getArgument('year')) {
            if (empty($month)) {
                throw new \Exception('Year can not be empty');
            }
        }
    }
}
