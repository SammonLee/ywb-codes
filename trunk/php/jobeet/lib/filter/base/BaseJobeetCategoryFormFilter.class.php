<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * JobeetCategory filter form base class.
 *
 * @package    jobeet
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormFilterGeneratedTemplate.php 13459 2008-11-28 14:48:12Z fabien $
 */
class BaseJobeetCategoryFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'                           => new sfWidgetFormFilterInput(),
      'slug'                           => new sfWidgetFormFilterInput(),
      'jobeet_category_affiliate_list' => new sfWidgetFormPropelChoice(array('model' => 'JobeetAffiliate', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'name'                           => new sfValidatorPass(array('required' => false)),
      'slug'                           => new sfValidatorPass(array('required' => false)),
      'jobeet_category_affiliate_list' => new sfValidatorPropelChoice(array('model' => 'JobeetAffiliate', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('jobeet_category_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function addJobeetCategoryAffiliateListColumnCriteria(Criteria $criteria, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $criteria->addJoin(JobeetCategoryAffiliatePeer::CATEGORY_ID, JobeetCategoryPeer::ID);

    $value = array_pop($values);
    $criterion = $criteria->getNewCriterion(JobeetCategoryAffiliatePeer::AFFILIATE_ID, $value);

    foreach ($values as $value)
    {
      $criterion->addOr($criteria->getNewCriterion(JobeetCategoryAffiliatePeer::AFFILIATE_ID, $value));
    }

    $criteria->add($criterion);
  }

  public function getModelName()
  {
    return 'JobeetCategory';
  }

  public function getFields()
  {
    return array(
      'id'                             => 'Number',
      'name'                           => 'Text',
      'slug'                           => 'Text',
      'jobeet_category_affiliate_list' => 'ManyKey',
    );
  }
}
