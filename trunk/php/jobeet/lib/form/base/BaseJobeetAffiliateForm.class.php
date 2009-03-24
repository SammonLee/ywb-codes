<?php

/**
 * JobeetAffiliate form base class.
 *
 * @package    jobeet
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 12815 2008-11-09 10:43:58Z fabien $
 */
class BaseJobeetAffiliateForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                             => new sfWidgetFormInputHidden(),
      'url'                            => new sfWidgetFormInput(),
      'email'                          => new sfWidgetFormInput(),
      'token'                          => new sfWidgetFormInput(),
      'is_active'                      => new sfWidgetFormInputCheckbox(),
      'created_at'                     => new sfWidgetFormDateTime(),
      'jobeet_category_affiliate_list' => new sfWidgetFormPropelChoiceMany(array('model' => 'JobeetCategory')),
    ));

    $this->setValidators(array(
      'id'                             => new sfValidatorPropelChoice(array('model' => 'JobeetAffiliate', 'column' => 'id', 'required' => false)),
      'url'                            => new sfValidatorString(array('max_length' => 255)),
      'email'                          => new sfValidatorString(array('max_length' => 255)),
      'token'                          => new sfValidatorString(array('max_length' => 255)),
      'is_active'                      => new sfValidatorBoolean(),
      'created_at'                     => new sfValidatorDateTime(array('required' => false)),
      'jobeet_category_affiliate_list' => new sfValidatorPropelChoiceMany(array('model' => 'JobeetCategory', 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorPropelUnique(array('model' => 'JobeetAffiliate', 'column' => array('email')))
    );

    $this->widgetSchema->setNameFormat('jobeet_affiliate[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'JobeetAffiliate';
  }


  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['jobeet_category_affiliate_list']))
    {
      $values = array();
      foreach ($this->object->getJobeetCategoryAffiliates() as $obj)
      {
        $values[] = $obj->getCategoryId();
      }

      $this->setDefault('jobeet_category_affiliate_list', $values);
    }

  }

  protected function doSave($con = null)
  {
    parent::doSave($con);

    $this->saveJobeetCategoryAffiliateList($con);
  }

  public function saveJobeetCategoryAffiliateList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['jobeet_category_affiliate_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (is_null($con))
    {
      $con = $this->getConnection();
    }

    $c = new Criteria();
    $c->add(JobeetCategoryAffiliatePeer::AFFILIATE_ID, $this->object->getPrimaryKey());
    JobeetCategoryAffiliatePeer::doDelete($c, $con);

    $values = $this->getValue('jobeet_category_affiliate_list');
    if (is_array($values))
    {
      foreach ($values as $value)
      {
        $obj = new JobeetCategoryAffiliate();
        $obj->setAffiliateId($this->object->getPrimaryKey());
        $obj->setCategoryId($value);
        $obj->save();
      }
    }
  }

}
