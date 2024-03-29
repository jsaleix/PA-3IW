<div class="row" >
<div class="col-12 col-sm-12 col-md-12 col-xl-12">
        <div class="col-inner">
            <div class="pageTitle">
                <h2 style="font-weight: lighter;"><?=$pageTitle?></h2>
                <?php if(isset($createButton)): ?>
                    <a href="<?= $createButton['link']?>"><button class="cta-green"><?=$createButton['label']?></button></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="col-md-12">
        <div class="content w-100">
            <div class="calendar-container">
                <div class="calendar"> 
                <div class="year-header"> 
                    <span class="left-button fa fa-chevron-left" id="prev"> </span> 
                    <span class="year" id="label"></span> 
                    <span class="right-button fa fa-chevron-right" id="next"> </span>
                </div> 
                <table class="months-table w-100"> 
                    <tbody>
                    <tr class="months-row">
                        <td class="month">Jan</td> 
                        <td class="month">Feb</td> 
                        <td class="month">Mar</td> 
                        <td class="month">Apr</td> 
                        <td class="month">May</td> 
                        <td class="month">Jun</td> 
                        <td class="month">Jul</td>
                        <td class="month">Aug</td> 
                        <td class="month">Sep</td> 
                        <td class="month">Oct</td>          
                        <td class="month">Nov</td>
                        <td class="month">Dec</td>
                    </tr>
                    </tbody>
                </table> 
                
                <table class="days-table w-100"> 
                    <td class="day">Sun</td> 
                    <td class="day">Mon</td> 
                    <td class="day">Tue</td> 
                    <td class="day">Wed</td> 
                    <td class="day">Thu</td> 
                    <td class="day">Fri</td> 
                    <td class="day">Sat</td>
                </table> 
                <div class="frame"> 
                    <table class="dates-table w-100"> 
                    <tbody class="tbody">             
                    </tbody> 
                    </table>
                </div> 
                <button class="button" id="add-button">Add booking date</button>
                </div>
            </div>

            <div class="events-container"></div>

            <div class="dialog" id="dialog">
                <h2 class="dialog-header"> Add a new date </h2>
                <form class="form" id="form">
                    <div class="form-container" align="center">
                        <label class="form-label" id="valueFromMyButton" for="count">Time</label>
                        <input class="input" type="time" id="time">
                        <label class="form-label" id="valueFromMyButton" for="name">Informations</label>
                        <input class="input" type="text" id="name" maxlength="36">
                        <label class="form-label" id="valueFromMyButton" for="count">Number of people</label>
                        <input class="input" type="number" id="count" min="0" max="1000000" maxlength="7">
                        <input type="button" value="Cancel" class="button" id="cancel-button">
                        <input type="button" value="OK" class="button button-white" id="ok-button">
                    </div>
                </form>
            </div>
        </div>  
    </div>

</div>


<script src="/Assets/js/calendar.js"></script>
<link rel="stylesheet" href="/Assets/styles/calendar.css">