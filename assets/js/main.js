var jsonData = "1";
var isError = 0;
var fileName = "";

document.addEventListener("DOMContentLoaded", () => {
    addLog("Необходимо задать начальные данные");
    init();

});

function isJsonString(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}

// =========================================================================
// =========================================================================
// =========================================================================

async function addReport(message = "", isErrorMessage = 0) {
    isError = isErrorMessage;
    const errorPrefix = isErrorMessage ? "ОШИБКА! - " : "";
    const dbData = {};
    dbData["servername"] = document.getElementById("db-host").value;
    dbData["port"] = document.getElementById("db-port").value;
    dbData["dbname"] = document.getElementById("db-name").value;
    dbData["username"] = document.getElementById("db-user-name").value;
    dbData["password"] = document.getElementById("db-password").value;
    dbData["mess"] = errorPrefix + message;
    addLog(dbData["mess"], 1);
    await sendDataToDB(JSON.stringify(dbData));
    console.log(dbData);
    console.log(JSON.stringify(dbData));
}
function addLog(mess, sublevel = false) {
    console.log(mess);
    const logContainer = document.getElementById("log-container");
    if (!logContainer) {
        return;
    }
    logContainer.insertAdjacentHTML(
        "afterbegin",
        `<span class='log-line ${sublevel ? "sub-level" : ""}'>${sublevel ? " - " : ""
        }${mess}</span>`
    );
}
function clearLog(mess) {
    const logContainer = document.getElementById("log-container");
    logContainer.innerHTML = "";
}
function jsonReturn(data) {
    addLog(data);
    console.log(JSON.stringify(data));
}
async function sendDataToXMLSave(data) {
    url = "/save_xml.php";
    try {
        const response = await fetch(url, {
            method: "POST",
            headers: {
                Accept: "application/json",
                "Content-Type": "application/json",
            },
            body: data,
        });
        if (!response.ok) {
            // console.log("response error");
            // console.log(response.status);
            addReport(
                `Ошибка выполнения запроса на сохранение xml файла: ${response.status}`,
                1
            );
        } else {
            fileName = await response.text();
            addLog(
                `ссылка для скачивания файла <a href='uploads/${fileName}' target='_blank'>${fileName}</a>`,
                1
            );
            // console.log(fileName);
        }
    } catch (e) {
        console.log(e);
        addReport(`Ошибка выполнения запроса: ${e}`, 1);
    }
}
async function sendDataToFtpSave(data) {
    url = "/send_to_ftp.php";
    try {
        const response = await fetch(url, {
            method: "POST",
            headers: {
                Accept: "application/json",
                "Content-Type": "application/json",
            },
            body: data,
        });
        if (!response.ok) {
            // console.log("response error");
            // console.log(response.status);
            addReport(
                `Ошибка выполнения запроса отправки по ftp: ${url} ${response.status}`,
                1
            );
        } else {
            const serverResponse = await response.text();
            console.log(serverResponse);
            if (!isJsonString(serverResponse)) {
                addReport("Ошибка разбора строки ответа на шаге отправки по ftp", 1);
                return;
            }
            const answer = JSON.parse(serverResponse);
            if (answer["error"] == "0") {
                addLog(`${answer["description"]}`, 1);
            } else {
                addReport(`Ошибка отправки по ftp: ${answer["description"]}`, 1);
            }
            // addLog(`${serverResponse}`, 1);
            // console.log(serverResponse);
        }
    } catch (e) {
        console.log(e);
        addReport(`Ошибка выполнения запроса: ${e}`, 1);
    }
}
async function sendDataToDB(data) {
    console.log(data);
    url = "/db-utils.php";
    try {
        const response = await fetch(url, {
            method: "POST",
            headers: {
                Accept: "application/json",
                "Content-Type": "application/json",
            },
            body: data,
        });
        if (!response.ok) {
            console.log("response error");
            console.log(response.status);
        } else {
            const serverResponse = await response.text();
            addLog(`${serverResponse}`, 1);
            console.log(serverResponse);
        }
    } catch (e) {
        console.log(e);
    }
}
async function sendGetReq(urlMain, urlParam) {
    url = urlMain + encodeURIComponent(urlParam);
    // console.log(url);
    try {
        const response = await fetch(url, {
            method: "GET",
            headers: {
                "Content-Type": "text/plain;charset=UTF-8",
            },
        });
        // console.log(await response.text());
        if (!response.ok) {
            // console.log("response error");
            addReport(`Ошибка выполнения GET запроса: ${url} ${response.status}`, 1);
            // console.log(await response.text());
            // console.log(response.status);
        } else {
            jsonData = await response.text();
        }
    } catch (e) {
        console.log(e);
        addReport(`Ошибка выполнения запроса: ${e}`, 1);
    }
}
function progress(currentValue, sumaryValue) {
    const progress = document.getElementById("progress");
    const percent = (currentValue / sumaryValue) * 100;
    if (!currentValue && !sumaryValue) {
        progress.innerHTML = "";
        return;
    }
    progress.innerHTML = `<div class='progress-bar' role='progressbar' style='width: ${percent}%;' aria-valuenow='${percent}' aria-valuemin='0' aria-valuemax='100'>${currentValue} из ${sumaryValue}</div>`;
}
// =========================================================================
// =========================================================================
// =========================================================================
function init() {
    const actionButton = document.getElementById("main-action");
    if (actionButton) {
        actionButton.addEventListener("click", (e) => {
            e.preventDefault();
            clearLog();
            addLog("Начали обработку ...");
            allStep();
        });
    }
}
// =========================================================================
// =========================================================================
// =========================================================================
async function allStep() {
    // console.log(isError);
    isError = 0;
    const uslugiArray = await step1();
    // console.log(isError);
    if (typeof uslugiArray === undefined) {
        addReport("Ошибка получения массива для шага 2", 1);
        return;
    }
    if (isError) {
        return;
    }
    const uslugiRezultArray = step2(uslugiArray);
    if (typeof uslugiRezultArray === undefined) {
        addReport("Ошибка получения массива для шага 3", 1);
        return;
    }
    if (isError) {
        return;
    }
    const uslugiDetailtArray = await step3(uslugiRezultArray, 0);
    if (typeof uslugiDetailtArray === undefined) {
        addReport("Ошибка получения массива для шага 4", 1);
        return;
    }
    if (isError) {
        return;
    }
    step4(uslugiDetailtArray);
    await step5(uslugiDetailtArray);
    if (isError) {
        return;
    }
    await step6();
    await step7();
    // step8(uslugiDetailtArray);
}
async function step1() {
    addLog("1. Получение списка всех услуг");
    addLog("формируем запрос", 1);
    const basicUrl = document.getElementById("basic-url");
    if (!basicUrl) {
        addReport("Не найден элемент", 1);
        return;
    }
    await sendGetReq("/request.php?request=", basicUrl.value);
    if (!isJsonString(jsonData)) {
        addReport("Ошибка разбора строки ответа на шаге 1", 1);
        return;
    }
    const uslugiObj = JSON.parse(jsonData);
    // console.log(basicUrl.value);
    // console.log(uslugiObj['ulist']);
    // console.log(` Количество полученных услуг - ${Object.keys(uslugiObj['ulist']).length}`)
    if (typeof uslugiObj === undefined) {
        // addLog("Произошел сбой при получении данных...");
        addReport("Произошел сбой при получении данных...", 1);
        return null;
    }
    addLog(
        `количество полученных услуг - ${Object.keys(uslugiObj["ulist"]).length}`,
        1
    );
    return uslugiObj["ulist"];
}
function step2(uslugiArray) {
    addLog("2. Получение только нужных услуг");
    addLog("обработка массива, поиск has_electronic_view равно 1", 1);
    const resultArray = [];
    uslugiArray.forEach((element) => {
        if (element["has_electronic_view"] == 1) {
            resultArray.push(element["id"]);
        }
        // console.log(element['has_electronic_view']);
    });
    addLog(`услуг после обработки - ${resultArray.length}`, 1);
    return resultArray;
}
async function step3(uslugiArray, limit) {
    addLog("3. Получения информации по каждой услуге");
    // addLog('получение данных по каждой услуге', 1)
    const basicUrl = document.getElementById("basic-url-2");
    if (!basicUrl) {
        // addLog("Не найден элемент");
        addReport("Не найден элемент", 1);
        return;
    }
    const resultArray = [];
    let singleObject = {};
    // console.log(uslugiArray);
    // uslugiArray.forEach(async(element, index) => {
    const maxProgress = (limit || uslugiArray.length);
    let i = 0;
    for await (let element of uslugiArray) {
        // console.log(element);
        if (limit && i > limit) {
            // return
            break;
        }
        await sendGetReq("/request.php?request=", `${basicUrl.value + element}`);
        if (!isJsonString(jsonData)) {
            addReport("Ошибка разбора строки ответа на шаге 3", 1);
            return;
        }
        uslugiObj = JSON.parse(jsonData);
        // console.log("uslugiObj 0000");
        // console.log(uslugiObj);
        singleObject = {};
        singleObject["id"] = uslugiObj["id"];
        singleObject["name"] = uslugiObj["name"];
        singleObject["organization"] = uslugiObj["organization"];
        singleObject["payment"] = uslugiObj["description"]["payment"];
        singleObject["state_duty_payment"] =
            uslugiObj["description"]["state_duty_payment"];
        singleObject["has_electronic_view"] =
            uslugiObj["description"]["has_electronic_view"];
        // console.log('singleObject');
        // console.log(singleObject);
        resultArray.push(singleObject);
        // console.log('resultArray 0000');
        // console.log(resultArray);
        i++;
        progress(i, maxProgress);
    }
    // console.log('================');
    // console.log(resultArray);
    return resultArray;
}
function step4(detailArray) {
    addLog("4. Формирование массива в виде JSON строки");
    console.log(JSON.stringify(detailArray));
    setTimeout(function () {
        progress(0, 0);
    }, 500);
}
async function step5(detailArray) {
    addLog("5. Сохранение в xml файл");
    // const InputJSON = JSON.stringify(detailArray)
    // const output = eval('OBJtoXML(' + InputJSON + ');')
    console.log(detailArray);
    await sendDataToXMLSave(JSON.stringify(detailArray));
}
async function step6() {
    addLog("6. Загрузка файла на сервер ftp");
    const ftpData = {};
    ftpData["host"] = document.getElementById("ftp-host").value;
    ftpData["user"] = document.getElementById("ftp-user-name").value;
    ftpData["pass"] = document.getElementById("ftp-password").value;
    ftpData["filename"] = fileName;
    // ftpData['filename'] = '2022-04-25 02-17-44.xml'
    console.log(ftpData);
    console.log(JSON.stringify(ftpData));
    await sendDataToFtpSave(JSON.stringify(ftpData));
}
async function step7() {
    addLog("7. Добавляем запись в БД о времени доступа к сервису ");
    addLog("Тестирование сервиса завершено.");
    if (!isError) {
        await addReport("- Все прошло успешно.");
    } else {
        addLog("Во время выполнения произошли ошибки!");
    }
}
    // function step8(detailArray) {
    //     addLog('8. Сформированный массив в виде JSON ');
    //     // const InputJSON = JSON.stringify(detailArray)
    //     // const output = eval('OBJtoXML(' + InputJSON + ');')
    //     // console.log(output)
    //     addLog(JSON.stringify(detailArray));
// }