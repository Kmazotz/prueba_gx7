<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220713075131 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE empleado_rol ADD empleado_id INT DEFAULT NULL, ADD role_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE empleado_rol ADD CONSTRAINT FK_43CD4CEF952BE730 FOREIGN KEY (empleado_id) REFERENCES empleado (id)');
        $this->addSql('ALTER TABLE empleado_rol ADD CONSTRAINT FK_43CD4CEFD60322AC FOREIGN KEY (role_id) REFERENCES roles (id)');
        $this->addSql('CREATE INDEX IDX_43CD4CEF952BE730 ON empleado_rol (empleado_id)');
        $this->addSql('CREATE INDEX IDX_43CD4CEFD60322AC ON empleado_rol (role_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE empleado_rol DROP FOREIGN KEY FK_43CD4CEF952BE730');
        $this->addSql('ALTER TABLE empleado_rol DROP FOREIGN KEY FK_43CD4CEFD60322AC');
        $this->addSql('DROP INDEX IDX_43CD4CEF952BE730 ON empleado_rol');
        $this->addSql('DROP INDEX IDX_43CD4CEFD60322AC ON empleado_rol');
        $this->addSql('ALTER TABLE empleado_rol DROP empleado_id, DROP role_id');
    }
}
