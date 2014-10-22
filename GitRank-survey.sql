-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Mer 22 Octobre 2014 à 13:04
-- Version du serveur: 5.5.40-0ubuntu0.14.04.1
-- Version de PHP: 5.5.9-1ubuntu4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `GitRank-survey`
--

-- --------------------------------------------------------

--
-- Structure de la table `answer`
--

DROP TABLE IF EXISTS `answer`;
CREATE TABLE IF NOT EXISTS `answer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `grade` int(11) NOT NULL,
  `visitor_id` int(11) NOT NULL,
  `explanation` text NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `project`
--

DROP TABLE IF EXISTS `project`;
CREATE TABLE IF NOT EXISTS `project` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `link_github` varchar(255) NOT NULL,
  `nb_answers` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=53 ;

--
-- Contenu de la table `project`
--

INSERT INTO `project` (`id`, `link_github`, `nb_answers`) VALUES
(1, 'darul75/ng-slider', 0),
(2, 'danielcrisp/angular-rangeslider', 0),
(3, 'egorkhmelev/jslider', 0),
(4, 'prajwalkman/angular-slider', 0),
(5, 'PopSugar/angular-slider', 0),
(6, 'Venturocket/angular-slider', 0),
(7, 'angular-ui/ui-slider', 0),
(8, 'seiyria/bootstrap-slider', 0),
(9, 'CreateJS/EaselJS', 0),
(10, 'mendhak/angular-intro.js', 0),
(11, 'usablica/intro.js', 0),
(12, 'angular/angular.js', 0),
(13, 'jashkenas/backbone', 0),
(14, 'emberjs/ember.js', 0),
(15, 'knockout/knockout', 0),
(16, 'tastejs/todomvc', 0),
(17, 'spine/spine', 0),
(18, 'Polymer/polymer', 0),
(19, 'mozbrick/brick', 0),
(20, 'facebook/react', 0),
(21, 'sproutcore/sproutcore', 0),
(22, 'meteor/meteor', 0),
(23, 'yahoo/mojito', 0),
(24, 'bitovi/canjs', 0),
(25, 'derbyjs/derby', 0),
(26, 'gka/chroma.js', 0),
(27, 'mbostock/d3', 0),
(28, 'benpickles/peity', 0),
(29, 'okfn/recline', 0),
(30, 'jacomyal/sigma.js', 0),
(31, 'samizdatco/arbor', 0),
(32, 'HumbleSoftware/envisionjs', 0),
(33, 'kartograph/kartograph.js', 0),
(34, 'trifacta/vega', 0),
(35, 'stamen/modestmaps-js', 0),
(36, 'Leaflet/Leaflet', 0),
(37, 'GoodBoyDigital/pixi.js', 0),
(38, 'photonstorm/phaser', 0),
(39, 'melonjs/melonJS', 0),
(40, 'gamelab/kiwi.js', 0),
(41, 'craftyjs/Crafty', 0),
(42, 'goldfire/howler.js', 0),
(43, 'wellcaffeinated/PhysicsJS', 0),
(44, 'piqnt/cutjs', 0),
(45, 'cocos2d/cocos2d-html5', 0),
(46, 'playcanvas/engine', 0),
(47, 'mishoo/UglifyJS', 0),
(48, 'google/closure-library', 0),
(49, 'jquery/jquery', 0),
(50, 'blueimp/JavaScript-MD5', 0),
(51, 'jashkenas/underscore', 0),
(52, 'Sage/streamlinejs', 0);

-- --------------------------------------------------------

--
-- Structure de la table `visitor`
--

DROP TABLE IF EXISTS `visitor`;
CREATE TABLE IF NOT EXISTS `visitor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;