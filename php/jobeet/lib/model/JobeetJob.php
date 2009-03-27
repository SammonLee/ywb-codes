<?php

class JobeetJob extends BaseJobeetJob
{
  public function save(PropelPDO $con = null)
  {
    if ($this->isNew() && !$this->getExpiresAt())
    {
      $now = $this->getCreatedAt() ? $this->getCreatedAt('U') : time();
      $this->setExpiresAt($now + 86400 * sfConfig::get('app_active_days'));
    }
    if (!$this->getToken())
    {
      $this->setToken(sha1($this->getEmail().rand(11111, 99999)));
    } 
    return parent::save($con);
  }

  public function __toString()
  {
    return sprintf('%s at %s (%s)', $this->getPosition(), $this->getCompany(), $this->getLocation());
  }
  public function getCompanySlug()
  {
      return Jobeet::slugify($this->getCompany());
  }
 
  public function getPositionSlug()
  {
      return Jobeet::slugify($this->getPosition());
  }
 
  public function getLocationSlug()
  {
      return Jobeet::slugify($this->getLocation());
  }
  public function getTypeName()
  {
    return $this->getType() ? JobeetJobPeer::$types[$this->getType()] : '';
  }
   
  public function isExpired()
  {
    return $this->getDaysBeforeExpires() < 0;
  }
   
  public function expiresSoon()
  {
    return $this->getDaysBeforeExpires() < 5;
  }
   
  public function getDaysBeforeExpires()
  {
    return floor(($this->getExpiresAt('U') - time()) / 86400);
  }
  public function publish()
  {
    $this->setIsActivated(true);
    $this->save();
  }
}
