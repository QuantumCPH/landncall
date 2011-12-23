<?php
class sfCultureFilter extends sfFilter
{
	/**
	* Execute filter
	*
	* @param FilterChain $filterChain The symfony filter chain
	*/
	public function execute ($filterChain)
	{
		$supported_cultures = array('da_DK', 'pl_PL');
		
		$context = $this->getContext();
		$request = $context->getRequest();
		
		if (in_array($request->getParameter('lang'), $supported_cultures))
		{
			$context->getUser()->setCulture($request->getParameter('lang'));
		}
		else
			$context->getUser()->setCulture('da_DK');
		
		return $filterChain->execute();
	}
}