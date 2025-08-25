<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250825153514 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("INSERT INTO user (username, roles, password) VALUES ('admin', '[\"ROLE_ADMIN\"]', '\$2y\$13\$UZ4h7gp6cCU.toRuHSgflO8/guy6wVucd/WFhaAsNDR1zWexpFFbq')");

    }

    public function down(Schema $schema): void
    {
        $this->addSql("DELETE FROM user WHERE username = 'admin'");
    }
}
