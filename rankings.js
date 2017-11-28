//Write a function that allows us to add commas to raw numbers in spreadsheet for display.  2413 becomes 2,413.
        function addCommas(nStr)
        {
        	nStr += '';
        	x = nStr.split('.');
        	x1 = x[0];
        	x2 = x.length > 1 ? '.' + x[1] : '';
        	var rgx = /(\d+)(\d{3})/;
        	while (rgx.test(x1)) {
        		x1 = x1.replace(rgx, '$1' + ',' + '$2');
        	}
        	return x1 + x2;
        }
 
        //this will be executed after we "fetch" the contents of a spreadsheet. The json parameter will hold our spreadsheet's data
        function displayContent(json) {
            //start an html table and write out our headers.  Using <td> tags so it isn't bold and centered, which is the <th> default.
            var pre_html = '<table class="table table-striped table-flip-scroll cf" id = "salaries"><tr style="font-size: 20px;"><td>Rank</td><td>Team</td>\
    <td>Points</td>\
    <td class="vballremove">Wins</td><td class="vballremove">Losses</td><td class="vballremove">Forfeit</td></thead><tbody>';
            //Create an empty string to hold the HTML. We will put table data here.
            var actual_html='';
            //After we grab the table, close the HTML table.
            var post_html = '</tbody></table>'
            //figure out how many rows our spreadsheet has
            var len = json.feed.entry.length
 
            //loop through the spreadsheet, gathering data
            for (var i=0; i<len; i++) {
                //for each row, add the following to actual HTML, grabbing it as a list, and then joining the list together as one long string.
                //Uses HTML for table cells, and then grabs attributes from the spreadsheet, using appropriate syntax. Enter your table header in the Google spreadsheet between            
                //the gsx$ and .$t.
                actual_html += [
                    '<tr><td>', 
                     
                    json.feed.entry[i].gsx$rank.$t, 
                    '</td><td>', 
                    json.feed.entry[i].gsx$team.$t, 
                     
                    '</td><td>', 
                    
                    json.feed.entry[i].gsx$points.$t, 
                    '</td><td class="vballremove">', 
                    json.feed.entry[i].gsx$wins.$t, 
                    '</td><td class="vballremove">',
                    json.feed.entry[i].gsx$losses.$t, 
                    '</td><td class="vballremove">',
                    json.feed.entry[i].gsx$forfeit.$t, 
                     
                    '</td>',  '</tr>'
                ].join('');  
            }
            //put all three of our HTML strings into our div we made at the top of the page
            document.getElementById('salary_table_container').innerHTML = pre_html + actual_html + post_html
 
        }   
		
		<!--- Start of Tuesday --->
		//Write a function that allows us to add commas to raw numbers in spreadsheet for display.  2413 becomes 2,413.
        function addCommas(nStr)
        {
        	nStr += '';
        	x = nStr.split('.');
        	x1 = x[0];
        	x2 = x.length > 1 ? '.' + x[1] : '';
        	var rgx = /(\d+)(\d{3})/;
        	while (rgx.test(x1)) {
        		x1 = x1.replace(rgx, '$1' + ',' + '$2');
        	}
        	return x1 + x2;
        }
 
        //this will be executed after we "fetch" the contents of a spreadsheet. The json parameter will hold our spreadsheet's data
        function displayContent2(json) {
            //start an html table and write out our headers.  Using <td> tags so it isn't bold and centered, which is the <th> default.
            var pre_html = '<table class="table table-striped table-flip-scroll cf" id = "salaries2"><tr style="font-size: 20px;"><td>Rank</td><td>Team</td>\
    <td>Points</td>\
    <td class="vballremove">Wins</td><td class="vballremove">Losses</td><td class="vballremove">Forfeit</td></thead><tbody>';
            //Create an empty string to hold the HTML. We will put table data here.
            var actual_html='';
            //After we grab the table, close the HTML table.
            var post_html = '</tbody></table>'
            //figure out how many rows our spreadsheet has
            var len = json.feed.entry.length
 
            //loop through the spreadsheet, gathering data
            for (var i=0; i<len; i++) {
                //for each row, add the following to actual HTML, grabbing it as a list, and then joining the list together as one long string.
                //Uses HTML for table cells, and then grabs attributes from the spreadsheet, using appropriate syntax. Enter your table header in the Google spreadsheet between            
                //the gsx$ and .$t.
                actual_html += [
                    '<tr><td>', 
                     
                    json.feed.entry[i].gsx$ranktues.$t, 
                    '</td><td>', 
                    json.feed.entry[i].gsx$teamtues.$t, 
                     
                    '</td><td>', 
                    
                    json.feed.entry[i].gsx$pointstues.$t, 
                    '</td><td class="vballremove">', 
                    json.feed.entry[i].gsx$winstues.$t, 
                    '</td><td class="vballremove">',
                    json.feed.entry[i].gsx$lossestues.$t, 
                    '</td><td class="vballremove">',
                    json.feed.entry[i].gsx$forfeittues.$t, 
                     
                    '</td>',  '</tr>'
                ].join('');  
            }
            //put all three of our HTML strings into our div we made at the top of the page
            document.getElementById('salary_table_container2').innerHTML = pre_html + actual_html + post_html
 
        }   
		
		<!--- Start of Wednesday --->
		//Write a function that allows us to add commas to raw numbers in spreadsheet for display.  2413 becomes 2,413.
        function addCommas(nStr)
        {
        	nStr += '';
        	x = nStr.split('.');
        	x1 = x[0];
        	x2 = x.length > 1 ? '.' + x[1] : '';
        	var rgx = /(\d+)(\d{3})/;
        	while (rgx.test(x1)) {
        		x1 = x1.replace(rgx, '$1' + ',' + '$2');
        	}
        	return x1 + x2;
        }
 
        //this will be executed after we "fetch" the contents of a spreadsheet. The json parameter will hold our spreadsheet's data
        function displayContent3(json) {
            //start an html table and write out our headers.  Using <td> tags so it isn't bold and centered, which is the <th> default.
            var pre_html = '<table class="table table-striped table-flip-scroll cf" id = "salaries3"><tr style="font-size: 20px;"><td>Rank</td><td>Team</td>\
    <td>Points</td>\
    <td class="vballremove">Wins</td><td class="vballremove">Losses</td><td class="vballremove">Forfeit</td></thead><tbody>';
            //Create an empty string to hold the HTML. We will put table data here.
            var actual_html='';
            //After we grab the table, close the HTML table.
            var post_html = '</tbody></table>'
            //figure out how many rows our spreadsheet has
            var len = json.feed.entry.length
 
            //loop through the spreadsheet, gathering data
            for (var i=0; i<len; i++) {
                //for each row, add the following to actual HTML, grabbing it as a list, and then joining the list together as one long string.
                //Uses HTML for table cells, and then grabs attributes from the spreadsheet, using appropriate syntax. Enter your table header in the Google spreadsheet between            
                //the gsx$ and .$t.
                actual_html += [
                    '<tr><td>', 
                     
                    json.feed.entry[i].gsx$rank.$t, 
                    '</td><td>', 
                    json.feed.entry[i].gsx$team.$t, 
                     
                    '</td><td>', 
                    
                    json.feed.entry[i].gsx$points.$t, 
                    '</td><td class="vballremove">', 
                    json.feed.entry[i].gsx$wins.$t, 
                    '</td><td class="vballremove">',
                    json.feed.entry[i].gsx$losses.$t, 
                    '</td><td class="vballremove">',
                    json.feed.entry[i].gsx$forfeit.$t, 
                     
                    '</td>',  '</tr>'
                ].join('');  
            }
            //put all three of our HTML strings into our div we made at the top of the page
            document.getElementById('salary_table_container3').innerHTML = pre_html + actual_html + post_html
 
        }   
		
<!--- Start of Thursday --->
		//Write a function that allows us to add commas to raw numbers in spreadsheet for display.  2413 becomes 2,413.
        function addCommas(nStr)
        {
        	nStr += '';
        	x = nStr.split('.');
        	x1 = x[0];
        	x2 = x.length > 1 ? '.' + x[1] : '';
        	var rgx = /(\d+)(\d{3})/;
        	while (rgx.test(x1)) {
        		x1 = x1.replace(rgx, '$1' + ',' + '$2');
        	}
        	return x1 + x2;
        }
 
        //this will be executed after we "fetch" the contents of a spreadsheet. The json parameter will hold our spreadsheet's data
        function displayContent4(json) {
            //start an html table and write out our headers.  Using <td> tags so it isn't bold and centered, which is the <th> default.
            var pre_html = '<table class="table table-striped table-flip-scroll cf" id = "salaries4"><tr style="font-size: 20px;"><td>Rank</td><td>Team</td>\
    <td>Points</td>\
    <td class="vballremove">Wins</td><td class="vballremove">Losses</td><td class="vballremove">Forfeit</td></thead><tbody>';
            //Create an empty string to hold the HTML. We will put table data here.
            var actual_html='';
            //After we grab the table, close the HTML table.
            var post_html = '</tbody></table>'
            //figure out how many rows our spreadsheet has
            var len = json.feed.entry.length
 
            //loop through the spreadsheet, gathering data
            for (var i=0; i<len; i++) {
                //for each row, add the following to actual HTML, grabbing it as a list, and then joining the list together as one long string.
                //Uses HTML for table cells, and then grabs attributes from the spreadsheet, using appropriate syntax. Enter your table header in the Google spreadsheet between            
                //the gsx$ and .$t.
                actual_html += [
                    '<tr><td>', 
                     
                    json.feed.entry[i].gsx$rank.$t, 
                    '</td><td>', 
                    json.feed.entry[i].gsx$team.$t, 
                     
                    '</td><td>', 
                    
                    json.feed.entry[i].gsx$points.$t, 
                    '</td><td class="vballremove">', 
                    json.feed.entry[i].gsx$wins.$t, 
                    '</td><td class="vballremove">',
                    json.feed.entry[i].gsx$losses.$t, 
                    '</td><td class="vballremove">',
                    json.feed.entry[i].gsx$forfeit.$t, 
                     
                    '</td>',  '</tr>'
                ].join('');  
            }
            //put all three of our HTML strings into our div we made at the top of the page
            document.getElementById('salary_table_container4').innerHTML = pre_html + actual_html + post_html
 
        }   
		
		<!--- Start of Friday --->
		//Write a function that allows us to add commas to raw numbers in spreadsheet for display.  2413 becomes 2,413.
        function addCommas(nStr)
        {
        	nStr += '';
        	x = nStr.split('.');
        	x1 = x[0];
        	x2 = x.length > 1 ? '.' + x[1] : '';
        	var rgx = /(\d+)(\d{3})/;
        	while (rgx.test(x1)) {
        		x1 = x1.replace(rgx, '$1' + ',' + '$2');
        	}
        	return x1 + x2;
        }
 
        //this will be executed after we "fetch" the contents of a spreadsheet. The json parameter will hold our spreadsheet's data
        function displayContent5(json) {
            //start an html table and write out our headers.  Using <td> tags so it isn't bold and centered, which is the <th> default.
            var pre_html = '<table class="table table-striped table-flip-scroll cf" id = "salaries5"><tr style="font-size: 20px;"><td>Rank</td><td>Team</td>\
    <td>Points</td>\
    <td class="vballremove">Wins</td><td class="vballremove">Losses</td><td class="vballremove">Forfeit</td></thead><tbody>';
            //Create an empty string to hold the HTML. We will put table data here.
            var actual_html='';
            //After we grab the table, close the HTML table.
            var post_html = '</tbody></table>'
            //figure out how many rows our spreadsheet has
            var len = json.feed.entry.length
 
            //loop through the spreadsheet, gathering data
            for (var i=0; i<len; i++) {
                //for each row, add the following to actual HTML, grabbing it as a list, and then joining the list together as one long string.
                //Uses HTML for table cells, and then grabs attributes from the spreadsheet, using appropriate syntax. Enter your table header in the Google spreadsheet between            
                //the gsx$ and .$t.
                actual_html += [
                    '<tr><td>', 
                     
                    json.feed.entry[i].gsx$rank.$t, 
                    '</td><td>', 
                    json.feed.entry[i].gsx$team.$t, 
                     
                    '</td><td>', 
                    
                    json.feed.entry[i].gsx$points.$t, 
                    '</td><td class="vballremove">', 
                    json.feed.entry[i].gsx$wins.$t, 
                    '</td><td class="vballremove">',
                    json.feed.entry[i].gsx$losses.$t, 
                    '</td><td class="vballremove">',
                    json.feed.entry[i].gsx$forfeit.$t, 
                     
                    '</td>',  '</tr>'
                ].join('');  
            }
            //put all three of our HTML strings into our div we made at the top of the page
            document.getElementById('salary_table_container5').innerHTML = pre_html + actual_html + post_html
 
        }   
		
		<!--- Start of Sunday --->
		//Write a function that allows us to add commas to raw numbers in spreadsheet for display.  2413 becomes 2,413.
        function addCommas(nStr)
        {
        	nStr += '';
        	x = nStr.split('.');
        	x1 = x[0];
        	x2 = x.length > 1 ? '.' + x[1] : '';
        	var rgx = /(\d+)(\d{3})/;
        	while (rgx.test(x1)) {
        		x1 = x1.replace(rgx, '$1' + ',' + '$2');
        	}
        	return x1 + x2;
        }
 
        //this will be executed after we "fetch" the contents of a spreadsheet. The json parameter will hold our spreadsheet's data
        function displayContent6(json) {
            //start an html table and write out our headers.  Using <td> tags so it isn't bold and centered, which is the <th> default.
            var pre_html = '<table class="table table-striped table-flip-scroll cf" id = "salaries6"><tr style="font-size: 20px;"><td>Rank</td><td>Team</td>\
    <td>Points</td>\
    <td class="vballremove">Wins</td><td class="vballremove">Losses</td><td class="vballremove">Forfeit</td></thead><tbody>';
            //Create an empty string to hold the HTML. We will put table data here.
            var actual_html='';
            //After we grab the table, close the HTML table.
            var post_html = '</tbody></table>'
            //figure out how many rows our spreadsheet has
            var len = json.feed.entry.length
 
            //loop through the spreadsheet, gathering data
            for (var i=0; i<len; i++) {
                //for each row, add the following to actual HTML, grabbing it as a list, and then joining the list together as one long string.
                //Uses HTML for table cells, and then grabs attributes from the spreadsheet, using appropriate syntax. Enter your table header in the Google spreadsheet between            
                //the gsx$ and .$t.
                actual_html += [
                    '<tr><td>', 
                     
                    json.feed.entry[i].gsx$rank.$t, 
                    '</td><td>', 
                    json.feed.entry[i].gsx$team.$t, 
                     
                    '</td><td>', 
                    
                    json.feed.entry[i].gsx$points.$t, 
                    '</td><td class="vballremove">', 
                    json.feed.entry[i].gsx$wins.$t, 
                    '</td><td class="vballremove">',
                    json.feed.entry[i].gsx$losses.$t, 
                    '</td><td class="vballremove">',
                    json.feed.entry[i].gsx$forfeit.$t, 
                     
                    '</td>',  '</tr>'
                ].join('');  
            }
            //put all three of our HTML strings into our div we made at the top of the page
            document.getElementById('salary_table_container6').innerHTML = pre_html + actual_html + post_html
 
        }   