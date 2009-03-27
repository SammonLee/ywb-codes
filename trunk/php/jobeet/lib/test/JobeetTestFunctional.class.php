<?php
// lib/test/JobeetTestFunctional.class.php
class JobeetTestFunctional extends sfTestFunctional
{
  public function loadData()
  {
    $loader = new sfPropelData();
    $loader->loadData(sfConfig::get('sf_test_dir').'/fixtures');
 
    return $this;
  }
 
  public function getMostRecentProgrammingJob()
  {
    // most recent job in the programming category
    $criteria = new Criteria();
    $criteria->add(JobeetCategoryPeer::SLUG, 'programming');
    $category = JobeetCategoryPeer::doSelectOne($criteria);
 
    $criteria = new Criteria();
    $criteria->add(JobeetJobPeer::EXPIRES_AT, time(), Criteria::GREATER_THAN);
    $criteria->addDescendingOrderByColumn(JobeetJobPeer::CREATED_AT);
 
    return JobeetJobPeer::doSelectOne($criteria);
  }
 
  public function getExpiredJob()
  {
    // expired job
    $criteria = new Criteria();
    $criteria->add(JobeetJobPeer::EXPIRES_AT, time(), Criteria::LESS_THAN);
 
    return JobeetJobPeer::doSelectOne($criteria);
  }
}
