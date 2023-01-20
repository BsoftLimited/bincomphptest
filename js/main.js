const results = document.getElementById("results");

const total = document.getElementById("total");
const state = document.getElementById("state");
const lga = document.getElementById("lga");

const create_ward = document.getElementById("create_ward");
const create_state = document.getElementById("create_state");
const create_lga = document.getElementById("create_lga");

const blue_buttons = document.querySelectorAll(".btn-outline-blue");
blue_buttons.forEach((blue_button) => {
    blue_button.addEventListener("mouseover", () => {
        blue_button.classList.remove("btn-outline-blue");
        blue_button.classList.add("btn-outline-blue-hover");
    });
    blue_button.addEventListener("mouseout", () => {
        blue_button.classList.remove("btn-outline-blue-hover");
        blue_button.classList.add("btn-outline-blue");
    });
});

function onClassListener(element, callback) {
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                callback(mutation.target);
            }
        });
    });
    observer.observe(element, { attributes: true });
    return observer.disconnect;
}

function getParties_from_results(data, lga){
    let parties = [];
    data.forEach((datum)=>{
        if(datum.polling.lga.name == lga){
            datum.results.unit.forEach((result)=>{
                if(!parties.includes(result.party)){
                    parties.push(result.party)
                }
            }); 
        } 
    });
    return parties;
}

function getParties_from_lga(lga){
    let parties = new Map();
    lga.wards.forEach((ward) =>{
        ward.pollings.forEach((polling)=>{
            polling.results.forEach((result) =>{
                if(!parties.has(result.party)){
                    parties.set(result.party, parseInt(result.score));
                }else{
                    parties.set(result.party, parseInt(result.score) + parties.get(result.party));
                }
            });
        });
    });
    return parties;
}

function init_charts(lga, parties) {
    let colors = ["#F44336", "#8E24AA", "#3949AB", "#039BE5", "#43A047", "#FDD835", "#FB8C00", "#546E7A", "#009688", "#009688", "#7C4DFF", "#512DA8"];
    try {
        var xValues = [];
        var yValues = [];
        var barColors = [];
        let index = 0;
        parties.forEach((score, party)=>{
            xValues.push(party);
            yValues.push(score);
            barColors.push(colors[index]);
            index += 1;
        });

        
        new Chart("pie", {
            type: "doughnut",
            data: { labels: xValues, datasets: [{ backgroundColor: barColors, data: yValues, fill: false }] },
            options: { title: { display: true, text: lga } }
        });
    }
    catch (ex) {
        console.log(ex.message);
    }
}


function initResults(pollings){		
    let host = "api/results.php";
    let params = { state_id: 25 };
    request(host, params, (status, response) =>{
        let data = JSON.parse(response);
        let currentState = '';
        let contents = '';
        let parties = []  ;
        data.forEach((datum)=>{
            if(currentState != datum.polling.lga.name){
                parties = getParties_from_results(data, datum.polling.lga.name);
                if(currentState != ''){
                    contents += `</tbody></table></p>`;
                }
                contents += `
                    <p class="mt-3">
                        <label style='font-size: 24px; font-weight: 500;'>${datum.polling.lga.state.name} - ${datum.polling.lga.name}</label>
                        <table class="table mb-2 px-3">
                            <thead>
                                <tr>
                                    <th scope="col">Number</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Ward</th>
                                    <th scope="col">Logitude</th>
                                    <th scope="col">Latitude</th>`;
                parties.forEach((party)=>{
                    contents += `<th scope="col">${party}</th>`;
                });
                contents += `<th scope="col">Date</th>
                                </tr>
                            </thead>
                            <tbody>`;
                currentState = datum.polling.lga.name;
            }
            
            contents += `<tr>
                            <td>${datum.polling.number}</td>
                            <td>${datum.polling.name}</td>
                            <td>${datum.polling.ward.name}</td>
                            <td>${datum.polling.long}</td>
                            <td>${datum.polling.lat}</td>`;

            let date = '';
            parties.forEach((party) => {
                let init = `<td>0</td>`;
                for(let index = 0; index < datum.results.unit.length; index++){
                    if(datum.results.unit[index].party === party){
                        init = `<td>${datum.results.unit[index].score}</td>`;
                        date = datum.results.unit[index].date;
                        break;
                    }
                }
                contents += init;
            });        
            contents += `<td>${new Date(date).toDateString()}</td></tr>`;
        });
        contents += `</tbody></table></p>`;
        pollings.innerHTML = contents;
    });
}

function showTotal(lga){
    parties = getParties_from_lga(lga);
    
    let contents = `</tbody></table></p>
        <p class="mt-3">
            <table class="table mb-2 px-3">
                <thead>
                    <tr>
                        <th scope="col">Polling Number</th>
                        <th scope="col">Polling Unit</th>
                        <th scope="col">Ward</th>`;
    parties.forEach((score, party)=>{
        contents += `<th scope="col">${party}</th>`;
    });
    contents += `</tr></thead><tbody>`;

    lga.wards.forEach((ward) =>{
        ward.pollings.forEach((polling)=>{
            if(polling.results.length > 0){
                contents += `<tr><td>${polling.number}</td><td>${polling.name}</td><td>${ward.name}</td>`;
                parties.forEach((score, party) => {
                    let init = `<td>0</td>`;
                    for(let index = 0; index < polling.results.length; index++){
                        if(polling.results[index].party === party){
                            init = `<td>${polling.results[index].score}</td>`;
                            break;
                        }
                    }
                    contents += init;
                }); 
                contents += `</tr>`;
            }
        });
    });

    contents += `<thead><tr><th scope="col"></th><th scope="col"></th><th scope="col">Total</th>`;
    parties.forEach((score, party)=>{
        contents += `<th scope="col">${score}</th>`;
    });
    contents += `</tr></thead>`;
    total.innerHTML = contents;

    init_charts(lga.name, parties);
}

function initTotals(){
    let data = [];
    let initLGAs = [];
    state.addEventListener("change", ()=>{
        lga.innerHTML = "<option selected>Select Local Government</option>";
        if(state.value == "" || state.value == "Select State"){
            if(!lga.disabled){
                lga.disabled = true;
            }
        }else{
            for(let i = 0; i < data.length; i++){
                if(data[i].id == state.value){
                    if(lga.disabled){
                        lga.disabled = false;
                    }

                    initLGAs = data[i].lgas;
                    let index = 0;
                    initLGAs.forEach((init)=>{
                        lga.innerHTML +=  `<option value="${index}">${init.name}</option>`;
                        index += 1;
                    });
                    break;
                }else{
                    console.log("no found");
                }
            }
        }
    });

    lga.addEventListener("change", ()=>{
        if((lga.value == "" || lga.value == "Select Local Government") && !total.innerHTML === ''){
            total.innerHTML = '';
        }else{
            showTotal(initLGAs[parseInt(lga.value)]);
        }
    });

    let host = "api/states/get.php";
    let params = { state_id: 25 };
    request(host, params, (status, response) =>{
        data = JSON.parse(response);

        let init = '<option selected>Select State</option>';
        data.forEach((state)=>{
            init += `<option value="${state.id}">${state.name}</option>`;
        });
        state.innerHTML = init;
    });
}

function initSignup(){
    let data = [];
    let initLGAs = [];
    let initWards = [];
    create_state.addEventListener("change", ()=>{
        create_lga.innerHTML = "<option selected>Select Local Government</option>";
        create_ward.innerHTML = "<option selected>Select Ward</option>";
        if(!(create_lga.value == "" || create_lga.value == "Select State")){

            initLGAs = data.states[parseInt(create_state.value)].lgas;
            let index = 0;
            initLGAs.forEach((init)=>{
                create_lga.innerHTML +=  `<option value="${index}">${init.name}</option>`;
                index += 1;
            });
        }
    });

    create_lga.addEventListener("change", ()=>{
        create_ward.innerHTML = "<option selected>Select Ward</option>";
        if(create_lga.value != "" && create_lga.value != "Select Local Government"){
            initWards = initLGAs[parseInt(create_lga.value)].wards;
            console.log(initWards.length);
            let index = 0;
            initWards.forEach((init)=>{
                create_ward.innerHTML +=  `<option value="${index}">${init.name}</option>`;
                index += 1;
            });
        }
    });

    let host = "api/all.php";
    let params = {};
    request(host, params, (status, response) =>{
        data = JSON.parse(response); 
        let init = '<option selected>Select State</option>';
        let index = 0;
        data.states.forEach((state)=>{
            init += `<option value="${index}">${state.name}</option>`;
            index += 1;
        });
        create_state.innerHTML = init;

        init = '<option selected>Select Party</option>';
        index = 0;
        data.parties.forEach((party)=>{
            init += `<option value="${index}">${party.name}</option>`;
            index += 1;
        });
        create_party.innerHTML = init;

    });
}