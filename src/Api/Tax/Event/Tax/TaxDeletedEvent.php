<?php declare(strict_types=1);

namespace Shopware\Api\Tax\Event\Tax;

use Shopware\Api\Entity\Write\DeletedEvent;
use Shopware\Api\Entity\Write\WrittenEvent;
use Shopware\Api\Tax\Definition\TaxDefinition;

class TaxDeletedEvent extends WrittenEvent implements DeletedEvent
{
    public const NAME = 'tax.deleted';

    public function getName(): string
    {
        return self::NAME;
    }

    public function getDefinition(): string
    {
        return TaxDefinition::class;
    }
}