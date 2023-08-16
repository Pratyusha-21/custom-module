<?php

namespace Drupal\movie\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\movie\MovieModuleInterface;

/**
 * Defines the movie module entity type.
 *
 * @ConfigEntityType(
 *   id = "movie_module",
 *   label = @Translation("Movie Module"),
 *   label_collection = @Translation("Movie Modules"),
 *   label_singular = @Translation("movie module"),
 *   label_plural = @Translation("movie modules"),
 *   label_count = @PluralTranslation(
 *     singular = "@count movie module",
 *     plural = "@count movie modules",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\movie\MovieModuleListBuilder",
 *     "form" = {
 *       "add" = "Drupal\movie\Form\MovieModuleForm",
 *       "edit" = "Drupal\movie\Form\MovieModuleForm",
 *       "delete" = "Drupal\Core\Entity\EntityDeleteForm"
 *     }
 *   },
 *   config_prefix = "movie_module",
 *   admin_permission = "administer movie_module",
 *   links = {
 *     "collection" = "/admin/structure/movie-module",
 *     "add-form" = "/admin/structure/movie-module/add",
 *     "edit-form" = "/admin/structure/movie-module/{movie_module}",
 *     "delete-form" = "/admin/structure/movie-module/{movie_module}/delete"
 *   },
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "description",
 *     "year",
 *     "movie",
 *   }
 * )
 */
class MovieModule extends ConfigEntityBase implements MovieModuleInterface {

  /**
   * The movie module ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The movie module label.
   *
   * @var string
   */
  protected $label;

  /**
   * The movie module status.
   *
   * @var bool
   */
  protected $status;

  /**
   * The movie_module description.
   *
   * @var string
   */
  protected $description;
  /**
   * The year of the movie.
   *
   * @var int
   */
  protected $year;
  /**
   * The referred movie.
   *
   * @var string
   */
  protected $movie;

}
