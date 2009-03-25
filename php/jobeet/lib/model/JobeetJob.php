<?php

class JobeetJob extends BaseJobeetJob
{
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
}
