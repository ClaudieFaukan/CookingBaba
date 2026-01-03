<?php

namespace App\Form;

use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\String\Slugger\SluggerInterface;

class FormListenerFactory
{

    public function __construct(private SluggerInterface $slugger)
    {
        
    }

    public function autoSlug(string $field): callable
    {
        return function (PreSubmitEvent $event) use($field): void {
            $data = $event->getData();

            if (isset($data[$field]) && !empty($data[$field]) && empty($data['slug'])) {
                $data['slug'] = $this->slugger->slug($data[$field])->lower();
                $event->setData($data);
            }
        };
    }

    public function timestamps(): callable
    {
        return function (PostSubmitEvent $event): void {
            $now = new \DateTimeImmutable();
            $data = $event->getData();

            if (! $data->getId()) {
                $data->setCreatedAt($now);
            }

            $data->setUpdatedAt($now);
        };
    }
}