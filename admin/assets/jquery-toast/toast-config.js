function showError(pMsg)
{
	$.toast({
		heading:"Error!",
		text:pMsg,
		position:"bottom-right",
		loaderBg:"#bf441d", icon:"error",
		hideAfter:3e3, stack:1,
	})
}
function showInfo(pMsg)
{
	$.toast({
		heading:"Info",
		text:pMsg,
		position:"bottom-right",
		loaderBg:"#3b98b5", icon:"info",
		hideAfter:3e3, stack:1,
	})
}
function showWarning(pMsg)
{
	$.toast({
		heading:"Warning!",
		text:pMsg,
		position:"bottom-right",
		loaderBg:"#da8609", icon:"warning",
		hideAfter:3e3, stack:1,
	})
}
function showSuccess(pMsg)
{
	$.toast({
		heading:"Success",
		text:pMsg,
		position:"bottom-right",
		loaderBg:"#5ba035", icon:"success",
		hideAfter:3e3, stack:1,
	})
}