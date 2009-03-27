<?php
// test/unit/model/JobeetJobTest.php
include(dirname(__FILE__).'/../../bootstrap/Propel.php');
 
$t = new lime_test(3, new lime_output_color());
$t->comment('->getCompanySlug()');
$job = JobeetJobPeer::doSelectOne(new Criteria());
$t->is($job->getCompanySlug(), Jobeet::slugify($job->getCompany()), '->getCompanySlug() return the slug for the company');

$t->comment('->save()');
$job = create_job();
$job->save();
$expiresAt = date('Y-m-d', time() + 86400 * sfConfig::get('app_active_days'));
$t->is($job->getExpiresAt('Y-m-d'), $expiresAt, '->save() updates expires_at if not set');
 
$job = create_job(array('expires_at' => '2008-08-08'));
$job->save();
$t->is($job->getExpiresAt('Y-m-d'), '2008-08-08', '->save() does not update expires_at if set');
 
function create_job($defaults = array())
{
  static $category = null;
 
  if (is_null($category))
  {
    $category = JobeetCategoryPeer::doSelectOne(new Criteria());
  }
 
  $job = new JobeetJob();
  $job->fromArray(array_merge(array(
    'category_id'  => $category->getId(),
    'company'      => 'Sensio Labs',
    'position'     => 'Senior Tester',
    'location'     => 'Paris, France',
    'description'  => 'Testing is fun',
    'how_to_apply' => 'Send e-Mail',
    'email'        => 'job@example.com',
    'token'        => rand(1111, 9999),
    'is_activated' => true,
  ), $defaults), BasePeer::TYPE_FIELDNAME);
 
  return $job;
}