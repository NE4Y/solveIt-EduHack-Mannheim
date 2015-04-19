$(function(){
	
	var elQuestionTextField = $("#question");
	var elMatchingPersons = $("#matching-persons");
	var elAskPublic = $("#ask-public");
	var elTplExpertPreview = $("#expert-preview");
	
	function mode_empty()
	{
		elQuestionTextField.val("");
		elMatchingPersons.fadeOut(300);
		elAskPublic.fadeOut(300);
	}

	function mode_search()
	{
		elMatchingPersons.fadeIn(300);
		elAskPublic.fadeIn(300);		
	}
	
	function clear_results()
	{
		elMatchingPersons.html("");
	}
	
	function addResult(result)
	{
		var elNewExpert = $(elTplExpertPreview.html());
		elNewExpert.find("img").attr("src", result.pic);
		elNewExpert.find("h3").text(result.name);
		elNewExpert.find("p").text(result.description);
		elMatchingPersons.append(elNewExpert);
	}
	
	function loadResults(query)
	{
		$.ajax({
	        url: 'ajax/search_person.php',
	        data: {q: query},
	        success: function(result) {
	        	clear_results();
	            result.forEach(function(r) {
	                addResult(r);
	            });
	        }
	    });
	}
	
	elAskPublic.hide();
	clear_results();
	mode_empty();
	
	elQuestionTextField.on("input", function(){
		var query = elQuestionTextField.val();
		if(query == "")
		{
			mode_empty();
		}
		else
		{
			mode_search();
			loadResults(query);
		}
	});
});