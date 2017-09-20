<?php

namespace Fesor\SchemaExample\Model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\{Id,Column,GeneratedValue,Entity};

/**
 * @Entity()
 */
class User
{
    /**
     * @var integer
     * @Id() @GeneratedValue() @Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @Column(type="string")
     */
    private $email;

    /**
     * @var string
     * @Column(type="string")
     */
    private $password;
}