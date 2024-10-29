// const fetchData = async () => {
//   console.log("asdf");
//   try {
//     const response = await fetch("http://localhost:8080/api/?file=apartaments");

//     console.log("response", response);

//     // Проверяем, успешен ли ответ
//     if (!response.ok) {
//       throw new Error("Сетевая ошибка: " + response.status);
//     }

//     const data = await response.json();
//     console.log(data); // Обработка полученных данных здесь

//     const krasnodar = data.filter((element) => {
//       return element.block_district == "6051dad145a04c1860dd40f2";
//     });
//     console.log("length", krasnodar.length);
//   } catch (error) {
//     console.error("Ошибка при получении данных:", error);
//   }
// };

// fetchData();

// async function send() {
//         console.log("send");
//         try {
//             // Получаем JSON с удаленного сервера
//             const response = await fetch('http://localhost:8080/api/?file=apartaments');
//             const jsonData = await response.json();

//             // Преобразуем JSON в строку
//             const jsonString = JSON.stringify(jsonData, null, 2); // Форматирование для читаемости

//             // Создаем Blob из строки JSON
//             const blob = new Blob([jsonString], { type: 'application/json' });

//             // Создаем URL для Blob
//             const url = URL.createObjectURL(blob);

//             // Создаем ссылку для скачивания
//             const a = document.createElement('a');
//             a.href = url;
//             a.download = 'buildings.json'; // Имя файла
//             document.body.appendChild(a);
//             a.click(); // Симулируем клик для скачивания
//             document.body.removeChild(a); // Удаляем ссылку

//             // Освобождаем URL
//             URL.revokeObjectURL(url);
//         } catch (error) {
//             console.error('Ошибка:', error);
//         }
//     };
//     send();
