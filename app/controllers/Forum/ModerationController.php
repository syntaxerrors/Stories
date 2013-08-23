<?php

class Forum_ModerationController extends BaseController {

	public function getDashboard()
	{
		// Get the data
		$reportedPostsCount = Forum_Moderation::count();
		$reportLogsCount    = Forum_Moderation_Log::count();

		$this->setViewData('reportedPostsCount', $reportedPostsCount);
		$this->setViewData('reportLogsCount', $reportLogsCount);
	}

	public function getReportedPosts()
	{
		$reportedPosts = Forum_Moderation::all();

		$this->setViewData('reportedPosts', $reportedPosts);
	}

	public function getReportLogs()
	{
		$reportLogs = Forum_Moderation_Log::orderBy('created_at', 'desc')->get();

		$this->setViewData('reportLogs', $reportLogs);
	}

	public function getRemoveReport($reportId)
	{
		// Get the report and delete it
		$report = Forum_Moderation::find($reportId);
		$report->delete();

		// Unset the moderation lock on the resource
		$resourceType = $report->resource_type;
		$resource     = $resourceType::find($report->resource_id);
		$resource->unsetModeration($report->id);

		return $this->redirect('forum/moderation/dashboard#reported-posts', 'Post successully removed from moderation.');
	}

	public function getAdminReview($reportId)
	{
		// Get the report and delete it
		$report                  = Forum_Moderation::find($reportId);
		$report->adminReviewFlag = 1;
		$this->checkErrorsSave($report);

		// Unset the moderation lock on the resource
		$resourceType = $report->resource_type;
		$resource     = $resourceType::find($report->resource_id);
		$resource->setAdminReview($report->id);

		return $this->redirect('forum/moderation/dashboard#reported-posts', 'Post successully submitted for admin review.');
	}
}