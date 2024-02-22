<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230706080816 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Weapon data';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("INSERT INTO `weapon` (`id`, `name`, `type`, `is_tool`, `is_open_map_weapon`, `image_name`) VALUES
                            (1, 'Bazooka', 0, 0, 0, 'Bazooka'),
                            (2, 'Grenade', 0, 0, 0, 'Grenade'),
                            (3, 'Mine Strike', 1, 0, 1, 'MineStrike'),
                            (4, 'Gas Grenade', 1, 0, 0, 'GasGrenade'),
                            (5, 'Sticky Grenade', 1, 0, 0, 'StickyGrenade'),
                            (6, 'Cluster Bomb', 0, 0, 0, 'ClusterBomb'),
                            (7, 'Shotgun', 0, 0, 0, 'Shotgun'),
                            (8, 'Uzi', 0, 0, 0, 'Uzi'),
                            (9, 'Minigun', 1, 0, 0, 'Minigun'),
                            (10, 'Mine', 0, 0, 0, 'Mine'),
                            (11, 'Dynamite', 0, 0, 0, 'Dynamite'),
                            (12, 'Baseball Bat', 0, 0, 0, 'BaseballBat'),
                            (13, 'Homing Missile', 0, 0, 0, 'HomingMissile'),
                            (14, 'Air Strike', 0, 0, 1, 'AirStrike'),
                            (15, 'Ninja Rope', 0, 1, 0, 'NinjaRope'),
                            (16, 'Parachute', 0, 1, 0, 'Parachute'),
                            (17, 'Jet Pack', 0, 1, 0, 'JetPack'),
                            (18, 'Fire Punch', 0, 0, 0, 'FirePunch'),
                            (19, 'Prod', 0, 0, 0, 'Prod'),
                            (20, 'Banana Bomb', 0, 0, 0, 'BananaBomb'),
                            (21, 'Super Banana Bomb', 1, 0, 0, 'SuperBananaBomb'),
                            (22, 'Sheep', 0, 0, 0, 'Sheep'),
                            (23, 'Super Sheep', 0, 0, 0, 'SuperSheep'),
                            (24, 'Concrete Donkey', 0, 0, 1, 'ConcreteDonkey'),
                            (25, 'Sheep-on-a-Rope', 0, 0, 0, 'SheepOnARope'),
                            (26, 'Holy Hand Grenade', 0, 0, 0, 'HolyHandGrenade'),
                            (27, 'Armageddon', 0, 0, 1, 'Armageddon'),
                            (28, 'Carpet Bomb', 0, 0, 1, 'CarpetBomb'),
                            (29, 'Sentry Gun', 0, 0, 0, 'SentryGun'),
                            (30, 'Bunker Buster', 0, 0, 1, 'BunkerBuster'),
                            (31, 'Old Lady', 0, 0, 0, 'OldLady'),
                            (32, 'Worm Stinger', 0, 0, 0, 'WormStinger'),
                            (33, 'Tasty Worm Lick', 0, 0, 0, 'TastyWormLick'),
                            (34, 'W-1 Rocket', 0, 0, 1, 'W1Rocket'),
                            (35, 'O.M.G Strike', 0, 0, 1, 'OMGStrike'),
                            (36, 'Ming Vase', 1, 0, 0, 'MingVase'),
                            (37, 'Kamikaze', 1, 0, 0, 'Kamikaze'),
                            (38, 'Dodgy Phone Battery', 0, 0, 0, 'DodgyPhoneBattery'),
                            (39, 'Sheep Launcher', 1, 0, 0, 'SheepLauncher'),
                            (40, 'Holy Mine Grenade', 1, 0, 0, 'HolyMineGrenade'),
                            (41, 'Cluster Bomb Mk2', 1, 0, 0, 'ClusterBombMk2'),
                            (42, 'Delayed Clusters', 1, 0, 0, 'DelayedClusters'),
                            (43, 'Industrial Electromagnet', 1, 1, 0, 'IndustrialElectromagnet'),
                            (44, 'Safe Teleport', 1, 1, 0, 'SafeTeleport'),
                            (45, 'Eco Jetpack', 1, 1, 0, 'JetPackEco'),
                            (46, 'Flaming Bat', 1, 0, 0, 'FlamingBat'),
                            (47, 'Alloy Baseball Bat', 1, 0, 0, 'AlloyBaseballBat'),
                            (48, 'Unwanted Present', 0, 0, 0, 'UnwantedPresent'),
                            (49, 'Super Flatulence Sheep', 1, 0, 0, 'SuperFlatulenceSheep'),
                            (50, 'Electric Sheep', 1, 0, 0, 'ElectricSheep'),
                            (51, 'Tri Bunker Buster', 1, 0, 1, 'TriBunkerBuster'),
                            (52, 'Mega Buster', 1, 0, 1, 'MegaBuster'),
                            (53, 'Stinking Carpet Bomb', 1, 0, 1, 'StinkingCarpetBomb'),
                            (54, 'Training Shotgun', 1, 0, 0, 'TrainingShotgun'),
                            (55, 'Triple Barrel Shotgun', 1, 0, 0, 'TripleBarrelShotgun'),
                            (56, 'Bazooka Pie', 1, 0, 0, 'BazookaPie'),
                            (57, 'Homing Clustile', 1, 0, 0, 'HomingClustile'),
                            (58, 'Demon Strike', 1, 0, 1, 'DemonStrike'),
                            (59, 'Concrete Angry Donkey', 1, 0, 1, 'ConcreteAngryDonkey'),
                            (60, 'Mega Punch', 1, 0, 0, 'MegaPunch'),
                            (61, 'Barbecued Sheep', 1, 0, 0, 'BarbecuedSheep'),
                            (62, 'Full Blow Torch', 1, 1, 0, 'FullBlowTorch'),
                            (63, 'Furious Prod', 1, 0, 0, 'FuriousProd'),
                            (64, 'Two-Handed Prod', 1, 0, 0, 'TwoHandedProd'),
                            (65, 'Agile Old Lady', 1, 0, 0, 'AgileOldLady'),
                            (66, 'Wormageddon', 1, 0, 1, 'Wormageddon'),
                            (67, 'Vehicle Mine', 1, 0, 0, 'VehicleMine'),
                            (68, 'Combat Parachute', 1, 1, 0, 'CombatParachute'),
                            (69, 'Party Balloon', 1, 1, 0, 'PartyBalloon'),
                            (70, 'Newbie Rope', 1, 1, 0, 'NewbieRope'),
                            (71, 'Worm Select Advantage', 1, 1, 0, 'WormSelectAdv'),
                            (72, 'Ninja Rope Pro', 1, 1, 0, 'NinjaRopePro'),
                            (73, 'Sentry Gun Lite', 1, 0, 0, 'SentryGunLite'),
                            (74, 'Poisoned Dynamite', 1, 0, 0, 'PoisonedDynamite'),
                            (75, 'Minions', 1, 0, 0, 'Minions'),
                            (76, 'Goat-on-a-Rope', 1, 0, 0, 'GoatOnARope'),
                            (77, 'Stuffed Turkey Surprise', 1, 0, 0, 'StuffedTurkeySurprise'),
                            (78, 'Liberty Strike', 1, 0, 1, 'LibertyStrike'),
                            (79, 'TF2 Sentry Gun', 1, 0, 0, 'TF2SentryGun'),
                            (80, 'Blow Torch', 0, 1, 0, 'BlowTorch'),
                            (81, 'Mischevious Drone', 0, 1, 0, 'Drone'),
                            (82, 'Electromagnet', 0, 1, 0, 'Electromagnet'),
                            (83, 'Girder', 0, 1, 0, 'Girder'),
                            (84, 'Girder Girth', 1, 1, 0, 'GirderGirth'),
                            (85, 'Teleport', 0, 1, 0, 'Teleport'),
                            (86, 'Tunnel Kit', 1, 1, 0, 'TunnelKit'),
                            (87, 'SG Magnetic Drone', 1, 1, 0, 'SGMagneticDrone'),
                            (89, 'Luzi', 1, 0, 0, 'Luzi'),
                            (90, 'O.M.G Strike LOL', 1, 0, 1, 'OMGLOLStrike'),
                            (91, 'Really Unwanted Present', 1, 0, 0, 'ReallyUnwantedPresent'),
                            (92, 'Sheep-on-a-long-Rope', 1, 0, 0, 'SheepOnALongRope'),
                            (93, 'Charged Battery', 1, 0, 0, 'ChargedBattery'),
                            (94, 'Worm Select', 0, 1, 0, 'WormSelect')");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('TRUNCATE TABLE weapon');
    }
}
