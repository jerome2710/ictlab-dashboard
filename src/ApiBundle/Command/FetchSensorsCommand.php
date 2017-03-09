<?php

namespace ApiBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FetchSensorsCommand extends ContainerAwareCommand
{
	/**
	 * Configure the command
	 */
	protected function configure()
	{
		$this->setName('ictlabdashboard:sensors:fetch')->setDescription('Fetch the sensor-details from the API');
	}

	/**
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 * @return int|null|void
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$command = new \ApiBundle\Domain\Messaging\Command\FetchSensorsCommand();

		$output->writeln('<info>Fetching sensor-details from the API...</info>');

		$this->getContainer()->get('command_bus')->handle($command);

		$output->writeln('<info>Done!</info>');
	}
}