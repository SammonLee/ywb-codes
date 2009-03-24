<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * JobeetAffiliate filter form base class.
 *
 * @package    jobeet
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormFilterGeneratedTemplate.php 13459 2008-11-28 14:48:12Z fabien $
 */
class BaseJobeetAffiliateFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'url'                            => new sfWidgetFormFilterInput(),
      'email'                          => new sfWidgetFormFilterInput(),
      'token'                          => new sfWidgetFormFilterInput(),
      'is_active'                      => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'created_at'                     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'jobeet_category_affiliate_list' => new sfWidgetFormPropelChoice(array('model' => 'JobeetCategory', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'url'                            => new sfValidatorPass(array('required' => false)),
      'email'                          => new sfValidatorPass(array('required' => false)),
      'token'                          => new sfValidatorPass(array('required' => false)),
      'is_active'                      => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'created_at'                     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'jobeet_category_affiliate_list' => new sfValidatorPropelChoice(array('model' => 'JobeetCategory', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('jobeet_affiliate_filters[%s]');

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

    $criteria->addJoin(JobeetCategoryAffiliatePeer::AFFILIATE_ID, JobeetAffiliatePeer::ID);

    $value = array_pop($values);
    $criterion = $criteria->getNewCriterion(JobeetCategoryAffiliatePeer::CATEGORY_ID, $value);

    foreach ($values as $value)
    {
      $criterion->addOr($criteria->getNewCriterion(JobeetCategoryAffiliatePeer::CATEGORY_ID, $value));
    }

    $criteria->add($criterion);
  }

  public function getModelName()
  {
    return 'JobeetAffiliate';
  }

  public function getFields()
  {
    return array(
      'id'                             => 'Number',
      'url'                            => 'Text',
      'email'                          => 'Text',
      'token'                          => 'Text',
      'is_active'                      => 'Boolean',
      'created_at'                     => 'Date',
      'jobeet_category_affiliate_list' => 'ManyKey',
    );
  }
}
