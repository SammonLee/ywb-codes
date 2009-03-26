<?php

class JobeetCategory extends BaseJobeetCategory
{
  public function getActiveJobs($limit=10)
  {
    $criteria = $this->getActiveJobsCriteria();
    $criteria->setLimit($limit);
   
    return JobeetJobPeer::doSelect($criteria);
  }

  public function getActiveJobsCriteria()
  {
    $criteria = new Criteria();
    $criteria->add(JobeetJobPeer::CATEGORY_ID, $this->getId());
   
    return JobeetJobPeer::addActiveJobsCriteria($criteria);
  }

  public function __toString()
  {
    return $this->getName();
  }

  public function getSlug()
  {
    return Jobeet::slugify($this->getName());
  }

  public function countActiveJobs()
  {
    $criteria = $this->getActiveJobsCriteria();
    return JobeetJobPeer::doCount($criteria);
  }

  public function setName($name)
  {
    parent::setName($name);
   
    $this->setSlug(Jobeet::slugify($name));
  }
}
