<?php

/**
 * category actions.
 *
 * @package    jobeet
 * @subpackage category
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class categoryActions extends sfActions
{
  public function executeShow(sfWebRequest $request)
  {
    $this->category = $this->getRoute()->getObject();

    $this->pager = new sfPropelPager(
      'JobeetJob',
      sfConfig::get('app_max_jobs_on_category')
    );
    $this->pager->setCriteria($this->category->getActiveJobsCriteria());
    $this->pager->setPage($request->getParameter('page', 1));
    $this->pager->init();
  }

}
