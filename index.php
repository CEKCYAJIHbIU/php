<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'sendDefault':
                if(isset($_POST['subject']) && isset($_POST['msg']) && isset($_POST['to']) && isset($_POST['name']) && isset($_POST['from'])) {
                    $subject = $_POST['subject'];
                    $message = $_POST['msg'];
                    $to = $_POST['to'];
                    $name = $_POST['name'];
                    $from = $_POST['from'];

                    $headers = "From: $name <$from>\r\n";
                    $headers .= "Content-type: text/plain; charset=utf-8\r\n";

                    try {
                        if(mail($to, $subject, $message, $headers)) {
                            echo "🟢 Доставлено";
                        } else {
                            echo "🔴 Ошибка";
                        }
                    } catch (Exception $e) {
                        echo "🔴 Ошибка | " . $e->getMessage();
                    }
                } else {
                    echo "🔴 Ошибка запроса";
                }
                break;

            case 'sendSnos':
                if (isset($_POST['names']) && 
                    isset($_POST['senders']) && 
                    isset($_POST['count']) && 
                    isset($_POST['titles']) && 
                    isset($_POST['to']) && 
                    isset($_POST['text'])) {
                    
                    $names = $_POST['names'];
                    $senders = $_POST['senders'];
                    $count = (int)$_POST['count'];
                    $titles = $_POST['titles'];
                    $to = $_POST['to'];
                    $text = $_POST['text'];
                    
                    if (!is_array($names) || !is_array($senders) || !is_array($titles) ||
                        empty($names) || empty($senders) || empty($titles)) {
                        echo "🔴 Ошибка: Пустые списки или неверные данные.";
                        exit;
                    }

                    $successCount = 0;

                    for ($i = 0; $i < $count; $i++) {
                        $randomName = $names[array_rand($names)];
                        $randomSender = $senders[array_rand($senders)];
                        $randomTitle = $titles[array_rand($titles)];

                        $headers = "From: $randomName <$randomSender>\r\n";
                        $headers .= "Content-type: text/plain; charset=utf-8\r\n";

                        if (mail($to, $randomTitle, $text, $headers)) {
                            $successCount++;
                        }
                    }

                    echo "🟢 Доставлено $successCount/$count";
                } else {
                    echo "🔴 Ошибка запроса: отсутствуют необходимые параметры.";
                }
                break;

            default:
                echo "🔴 Неизвестное действие";
                break;
        }
    } else {
        echo "🔴 Действие не указано";
    }
} else {
    echo "🔴 Неверный метод запроса";
}
?>
