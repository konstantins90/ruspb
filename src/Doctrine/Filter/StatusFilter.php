<?php
namespace App\Doctrine\Filter;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

class StatusFilter extends SQLFilter
{
    public function addFilterConstraint(
        ClassMetadata $targetEntity,
        $targetTableAlias
    )
    {    
        if ($targetEntity->hasField('status')) {
            return $targetTableAlias . '.status = 1';
        }
    
        return '';
    }
}
