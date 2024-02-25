<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240214062742 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande_semestrielle ADD CONSTRAINT FK_50061BC554432752 FOREIGN KEY (annee_previsionnelle_id) REFERENCES annee_previsionnelle (id)');
        $this->addSql('CREATE INDEX IDX_50061BC554432752 ON commande_semestrielle (annee_previsionnelle_id)');
        $this->addSql('ALTER TABLE commande_trimestrielle CHANGE annee_previsionnelle_id annee_previsionnelle_id INT DEFAULT NULL, CHANGE slug slug VARCHAR(5) NOT NULL');
        $this->addSql('ALTER TABLE commande_trimestrielle ADD CONSTRAINT FK_149D976154432752 FOREIGN KEY (annee_previsionnelle_id) REFERENCES annee_previsionnelle (id)');
        $this->addSql('CREATE INDEX IDX_149D976154432752 ON commande_trimestrielle (annee_previsionnelle_id)');
        $this->addSql('ALTER TABLE data_crenas CHANGE nbr_total_csb nbr_total_csb DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE data_crenas ADD CONSTRAINT FK_2715C59AA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE data_crenas_mois_projection_admission ADD CONSTRAINT FK_5B27E68B4D953FD1 FOREIGN KEY (data_crenas_id) REFERENCES data_crenas (id)');
        $this->addSql('ALTER TABLE data_crenas_mois_projection_admission ADD CONSTRAINT FK_5B27E68B907743C9 FOREIGN KEY (mois_projections_admissions_id) REFERENCES mois_projections_admissions (id)');
        $this->addSql('ALTER TABLE data_creni ADD CONSTRAINT FK_1635C816A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE data_creni_mois_projection_admission ADD CONSTRAINT FK_625042D62B7A8381 FOREIGN KEY (data_creni_id) REFERENCES data_creni (id)');
        $this->addSql('ALTER TABLE data_creni_mois_projection_admission ADD CONSTRAINT FK_625042D62FC19F60 FOREIGN KEY (creni_mois_projections_admissions_id) REFERENCES creni_mois_projections_admissions (id)');
        $this->addSql('ALTER TABLE pvrd ADD CONSTRAINT FK_4DD1D557B07B8B81 FOREIGN KEY (responsable_district_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE pvrd ADD CONSTRAINT FK_4DD1D557B08FA272 FOREIGN KEY (district_id) REFERENCES district (id)');
        $this->addSql('ALTER TABLE pvrd ADD CONSTRAINT FK_4DD1D55798260155 FOREIGN KEY (region_id) REFERENCES region (id)');
        $this->addSql('ALTER TABLE pvrd_produit ADD CONSTRAINT FK_82F488152292E50 FOREIGN KEY (pvrd_id) REFERENCES pvrd (id)');
        $this->addSql('ALTER TABLE pvrd_produit ADD CONSTRAINT FK_82F48815F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE reset_password_request CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_7CE748AA76ED395 ON reset_password_request (user_id)');
        $this->addSql('ALTER TABLE rma_nut CHANGE district_id district_id INT DEFAULT NULL, CHANGE region_id region_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE rma_nut ADD CONSTRAINT FK_22A49D4DA2B28FE8 FOREIGN KEY (uploaded_by_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE rma_nut ADD CONSTRAINT FK_22A49D4D9FEA9717 FOREIGN KEY (commande_trimestrielle_id) REFERENCES commande_trimestrielle (id)');
        $this->addSql('ALTER TABLE rma_nut ADD CONSTRAINT FK_22A49D4DB08FA272 FOREIGN KEY (district_id) REFERENCES district (id)');
        $this->addSql('ALTER TABLE rma_nut ADD CONSTRAINT FK_22A49D4D98260155 FOREIGN KEY (region_id) REFERENCES region (id)');
        $this->addSql('CREATE INDEX IDX_22A49D4D9FEA9717 ON rma_nut (commande_trimestrielle_id)');
        $this->addSql('CREATE INDEX IDX_22A49D4DB08FA272 ON rma_nut (district_id)');
        $this->addSql('CREATE INDEX IDX_22A49D4D98260155 ON rma_nut (region_id)');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL, CHANGE telephone telephone VARCHAR(10) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande_semestrielle DROP FOREIGN KEY FK_50061BC554432752');
        $this->addSql('DROP INDEX IDX_50061BC554432752 ON commande_semestrielle');
        $this->addSql('ALTER TABLE commande_trimestrielle DROP FOREIGN KEY FK_149D976154432752');
        $this->addSql('DROP INDEX IDX_149D976154432752 ON commande_trimestrielle');
        $this->addSql('ALTER TABLE commande_trimestrielle CHANGE annee_previsionnelle_id annee_previsionnelle_id INT NOT NULL, CHANGE slug slug VARCHAR(15) NOT NULL');
        $this->addSql('ALTER TABLE data_crenas DROP FOREIGN KEY FK_2715C59AA76ED395');
        $this->addSql('ALTER TABLE data_crenas CHANGE nbr_total_csb nbr_total_csb DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE data_crenas_mois_projection_admission DROP FOREIGN KEY FK_5B27E68B4D953FD1');
        $this->addSql('ALTER TABLE data_crenas_mois_projection_admission DROP FOREIGN KEY FK_5B27E68B907743C9');
        $this->addSql('ALTER TABLE data_creni DROP FOREIGN KEY FK_1635C816A76ED395');
        $this->addSql('ALTER TABLE data_creni_mois_projection_admission DROP FOREIGN KEY FK_625042D62B7A8381');
        $this->addSql('ALTER TABLE data_creni_mois_projection_admission DROP FOREIGN KEY FK_625042D62FC19F60');
        $this->addSql('ALTER TABLE pvrd DROP FOREIGN KEY FK_4DD1D557B07B8B81');
        $this->addSql('ALTER TABLE pvrd DROP FOREIGN KEY FK_4DD1D557B08FA272');
        $this->addSql('ALTER TABLE pvrd DROP FOREIGN KEY FK_4DD1D55798260155');
        $this->addSql('ALTER TABLE pvrd_produit DROP FOREIGN KEY FK_82F488152292E50');
        $this->addSql('ALTER TABLE pvrd_produit DROP FOREIGN KEY FK_82F48815F347EFB');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('DROP INDEX IDX_7CE748AA76ED395 ON reset_password_request');
        $this->addSql('ALTER TABLE reset_password_request CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE rma_nut DROP FOREIGN KEY FK_22A49D4DA2B28FE8');
        $this->addSql('ALTER TABLE rma_nut DROP FOREIGN KEY FK_22A49D4D9FEA9717');
        $this->addSql('ALTER TABLE rma_nut DROP FOREIGN KEY FK_22A49D4DB08FA272');
        $this->addSql('ALTER TABLE rma_nut DROP FOREIGN KEY FK_22A49D4D98260155');
        $this->addSql('DROP INDEX IDX_22A49D4D9FEA9717 ON rma_nut');
        $this->addSql('DROP INDEX IDX_22A49D4DB08FA272 ON rma_nut');
        $this->addSql('DROP INDEX IDX_22A49D4D98260155 ON rma_nut');
        $this->addSql('ALTER TABLE rma_nut CHANGE district_id district_id INT NOT NULL, CHANGE region_id region_id INT NOT NULL');
        $this->addSql('ALTER TABLE `user` CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_bin`, CHANGE telephone telephone VARCHAR(15) NOT NULL');
    }
}
