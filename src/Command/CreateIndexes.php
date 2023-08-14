<?php

namespace App\Command;

use App\Entity\Index;
use App\Service\IndexManager;
use App\Service\SymbolManager;
use App\Repository\IndexRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

#[AsCommand(
    name: 'app:create-indexes',
    description: 'Creates new indexes.',
    hidden: false
)]
class CreateIndexes extends Command
{
    /**
     * Constructor.
     */
    public function __construct(
        private SymbolManager $symbolManager,
        private IndexManager $indexManager
    ) {
        parent::__construct();
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->addOption('marketCap', 'mc', InputOption::VALUE_REQUIRED, 'Market capitalization');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $marketCap = $input->getOption('marketCap');
        if (!$marketCap) {
            $io->error('Market cap value not specified for filtering');

            return Command::FAILURE;
        }

        try {
            $symbols = $this->symbolManager->getFilteredList($marketCap);
            $this->indexManager->bulkCreateFromSymbols($symbols);
        } catch (\Exception|ClientExceptionInterface|RedirectionExceptionInterface|TransportExceptionInterface|ServerExceptionInterface $e) {
            $io->error($e->getMessage());

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
