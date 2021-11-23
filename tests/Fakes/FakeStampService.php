<?php

declare(strict_types=1);

namespace Celli33\JsonToCfdi\Tests\Fakes;

use Celli33\JsonToCfdi\StampService\StampServiceInterface;
use Celli33\JsonToCfdi\Values\Cfdi;
use Celli33\JsonToCfdi\Values\XmlContent;
use OutOfRangeException;

final class FakeStampService implements StampServiceInterface
{
    /**
     * @param array<Cfdi> $stampQueue
     */
    public function __construct(
        private array $stampQueue,
    ) {
    }

    public function stamp(XmlContent $preCfdi): Cfdi
    {
        return $this->stampQueuePop();
    }

    public function stampQueuePop(): Cfdi
    {
        $element = array_pop($this->stampQueue);
        if (null === $element) {
            throw new OutOfRangeException('Stamp queue is empty');
        }
        return $element;
    }

    public function stampQueueIsEmpty(): bool
    {
        return ([] === $this->stampQueue);
    }
}
