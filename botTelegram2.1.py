# -*- coding: utf-8 -*-
# переустановил requests, установил:
# pip install pysocks
# pip install pyTelegramBotAPI
# все, что работало в версии botFox1.0 перенес сюда. продолжаем прокачивать.
# менюшка кнопок на самом верху



import telebot, os, sys, time, socket, datetime
import psutil
from tqdm import tqdm



# # #   PATH  ХАРДКОР   # # #
path2graphix = r'graphix.txt'
path2gallery = r'gallery\masters' # r'D:\__sites__\4D' + \gallery\masters
rebootBat = r'C:\Users\server\Desktop\reboot.bat'


# # #   ГЛОБАЛЬНЫЕ   # # #
ids = {'231341109': 'Д.М.МИРОНОВ'} # ДОСТУПНЫЕ ID


telebot.apihelper.proxy = {'https':'socks5://14611055481:U777Vluhz8@orbtl.s5.opennetwork.cc:999'}
#telebot.apihelper.proxy = {'https':'socks5://login:pass@orbtl.s5.opennetwork.cc:port'}
#telebot.apihelper.proxy = {'https': 'socks5://login:pass@12.11.22.33:8000'}



# ФУНКЦИИ
def log(message):
    #now = datetime.datetime.today()
    now = datetime.datetime.strftime(datetime.datetime.now(), "%Y.%m.%d %H:%M:%S")
    line = f'{now} || {message}'
    with open('log.txt', 'a+') as f: f.write(f'{line}\n')
    print(line)

try:
    bot = telebot.TeleBot('509274649:AAFfyHLrYV-vnx14SkTYP4ylyYyvj8Vs1aY')
    bot.send_message(231341109, f"<code>Бот стартовал.</code>", parse_mode='HTML')
except:
    log('Не удается стартовать бота.')

    
def idControl():
    """Функция-декоратор контроль id"""
    def wrapper():
        print('проверка id')
        print(message.from_user.id)
        if str(message.from_user.id) not in ids:
            """ ФИЛЬТР ДОСТУПНЫХ ID'S """
            text = f'Для доступа к функционалу необходимо сообщить Ваш id администратору бота: {message.from_user.id}.'
            bot.send_message(message.chat.id, text)
        else:
            print(message.from_user.id)
            func()
    return wrapper


def countKP():
    #path2graphix = r'C:\Users\Dmitry\Dropbox\__python__\__044.CLEAR.YARD__\002-kp2autograph\graphix.txt'
    operators = {'Чистый двор':set(), 'РЭП-2':set()}
    #print(operators)
    columnNamesNumbs = {'Оператор':0, 'Расторгнут':0, 'Контейнерная площадка':0}

    with open(path2graphix, 'r') as f: lines = f.readlines()
    firstline = lines.pop(0)
    firstline = firstline.split('\t')
    #print(firstline)
    for i in columnNamesNumbs: columnNamesNumbs[i] = firstline.index(i)
    #print(columnNamesNumbs)
    for line in lines:
        line = line.split('\t')
        if columnNamesNumbs['Расторгнут'] =='Да': continue

        operators[line[columnNamesNumbs['Оператор']]].add(line[columnNamesNumbs['Контейнерная площадка']])
    return [len(operators['Чистый двор']), len(operators['РЭП-2'])]


def getLastDatePath():
    lastDate2 = [i for i in os.listdir(path2gallery) if '.db' not in i]
    lastDate = sorted(lastDate2)[-1]
    #lastDatePath = os.path.join(path2gallery, lastDate) # r'D:\__sites__\4D' + \gallery\masters\2019.12.12
    return os.path.join(path2gallery, lastDate)


##РАБОТАЕТ##
inline_bdays = telebot.types.InlineKeyboardMarkup(row_width=3)
days = []
def getDays(messagechatid, messageText):
    global days
    folders3 = [i for i in os.listdir(path2gallery) if i!='Thumbs.db']
    folders2 = list(set(folders3))
    days = sorted(folders2)
    for i in range(0, len(days), 3):
        try:
            btn1 = telebot.types.InlineKeyboardButton(days[i], callback_data=days[i])
            btn2 = telebot.types.InlineKeyboardButton(days[i + 1], callback_data=days[i + 1])
            btn3 = telebot.types.InlineKeyboardButton(days[i + 2], callback_data=days[i + 2])
            inline_bdays.add(btn1, btn2, btn3)
        except:
            try:
                btn1 = telebot.types.InlineKeyboardButton(days[i], callback_data=days[i])
                btn2 = telebot.types.InlineKeyboardButton(days[i + 1], callback_data=days[i + 1])
                inline_bdays.add(btn1, btn2)
            except:
                btn1 = telebot.types.InlineKeyboardButton(days[i], callback_data=days[i])
                inline_bdays.add(btn1)
    bot.send_message(messagechatid, messageText, parse_mode='Markdown', reply_markup=inline_bdays)


def startPopen():
    answer = []
    for process in psutil.process_iter():
        #print(process, process.pid, process.name())
        answer.append(f"name={process.name()} pid={process.pid}\n")
    answer = sorted(answer)
    return ''.join(answer)



# При команде /start выдает клавиатуру
@bot.message_handler(commands=['start', 'user']) #Юзерское меню
def request_location(message):
    log(f"id:{message.from_user.id}; text:{message.text}")
    if str(message.from_user.id) not in ids:
        """ ФИЛЬТР ДОСТУПНЫХ ID'S """
        log(f"НОВЫЙ id:{message.from_user.id}; text:{message.text}")
        text = f'Для доступа к функционалу необходимо сообщить Ваш id администратору бота: {message.from_user.id}.'
        bot.send_message(message.chat.id, text)
    else:
        markup = telebot.types.ReplyKeyboardMarkup(resize_keyboard=True)
        btn_gallery_txt = telebot.types.KeyboardButton('ГАЛЕРЕЯ txt')
        btn_gallery_img = telebot.types.KeyboardButton('ГАЛЕРЕЯ img')
        btn_gallery_allowed = telebot.types.KeyboardButton('ДАТЫ')
        user = telebot.types.KeyboardButton('/user') ### МЕНЮ ###
        logist = telebot.types.KeyboardButton('/logist') ### МЕНЮ ###
        admin = telebot.types.KeyboardButton('/admin') ### МЕНЮ ###
        btn_help = telebot.types.KeyboardButton('/help') ### МЕНЮ ###
        markup.row(btn_gallery_txt, btn_gallery_img, btn_gallery_allowed)
        markup.row(user, logist, admin, btn_help) ### МЕНЮ ###
        bot.send_message(message.chat.id, "Меню *user* активно", reply_markup=markup, parse_mode = 'Markdown')


@bot.message_handler(commands=['admin']) #админское меню
def adminMenu(message):
    log(f"id:{message.from_user.id}; text:{message.text}")
    if str(message.from_user.id) not in ids:
        """ ФИЛЬТР ДОСТУПНЫХ ID'S """
        log(f"НОВЫЙ id:{message.from_user.id}; text:{message.text}")
        text = f'Для доступа к функционалу необходимо сообщить Ваш id администратору бота: {message.from_user.id}.'
        bot.send_message(message.chat.id, text)
    else:
        markup = telebot.types.ReplyKeyboardMarkup(resize_keyboard=True)
        mimimi = telebot.types.KeyboardButton('server reboot')
        popen = telebot.types.KeyboardButton('popen')
        user = telebot.types.KeyboardButton('/user') ### МЕНЮ ###
        logist = telebot.types.KeyboardButton('/logist') ### МЕНЮ ###
        admin = telebot.types.KeyboardButton('/admin') ### МЕНЮ ###
        btn_help = telebot.types.KeyboardButton('/help') ### МЕНЮ ###
        markup.row(mimimi, popen)
        markup.row(user, logist, admin, btn_help) ### МЕНЮ ###
        bot.send_message(message.chat.id, "Меню *admin* активно", reply_markup=markup, parse_mode = 'Markdown')


@bot.message_handler(commands=['logist']) #логистическое меню
def request_location2(message):
    log(f"id:{message.from_user.id}; text:{message.text}")
    if str(message.from_user.id) not in ids:
        """ ФИЛЬТР ДОСТУПНЫХ ID'S """
        log(f"НОВЫЙ id:{message.from_user.id}; text:{message.text}")
        text = f'Для доступа к функционалу необходимо сообщить Ваш id администратору бота: {message.from_user.id}.'
        bot.send_message(message.chat.id, text)
    else:
        markup = telebot.types.ReplyKeyboardMarkup(resize_keyboard=True)
        kpQty = telebot.types.KeyboardButton('Кол-воКП')       # количество уникальных КП
        kontQty = telebot.types.KeyboardButton('Кол-во конт')   # количество кп по видам
        routes = telebot.types.KeyboardButton('Маршруты')  # маршруты
        user = telebot.types.KeyboardButton('/user')  ### МЕНЮ ###
        logist = telebot.types.KeyboardButton('/logist')  ### МЕНЮ ###
        admin = telebot.types.KeyboardButton('/admin')  ### МЕНЮ ###
        btn_help = telebot.types.KeyboardButton('/help')  ### МЕНЮ ###
        markup.row(kpQty, kontQty, routes)
        markup.row(user, logist, admin, btn_help)  ### МЕНЮ ###
        bot.send_message(message.chat.id, "Меню *logist* активно", reply_markup=markup, parse_mode = 'Markdown')


# работает
@bot.message_handler(commands=['help'])
def start_message(message):
    log(f"id:{message.from_user.id}; text:{message.text}")
    if str(message.from_user.id) not in ids:
        """ ФИЛЬТР ДОСТУПНЫХ ID'S """
        log(f"НОВЫЙ id:{message.from_user.id}; text:{message.text}")
        text = f'Для доступа к функционалу необходимо сообщить Ваш id администратору бота: {message.from_user.id}.'
        bot.send_message(message.chat.id, text)
    else:
        #print(message.chat.id)
        answer = f'Пользователь: *{ids[str(message.chat.id)]} (id {message.chat.id}), messagechatid: {message.chat.id}*'\
                 '\n\n*М Е Н Ю   /user:*\n' \
                 '1. *ГАЛЕРЕЯ txt* - вывести список неотработанных адресов из галереи за сегодняшний день\n' \
                 '2. *ГАЛЕРЕЯ img* - вывести подписанные изображения неотработанных адресов из галереи за сегодняшний день\n' \
                 '3. *ДАТЫ* - вывести на экран все доступные даты для дальнейшего просмотра содержимого\n' \
                 '\n*М Е Н Ю   /logist:*\n' \
                 '\n*М Е Н Ю   /admin:*\n'
        bot.send_message(message.chat.id, answer, parse_mode='Markdown')


# работает
@bot.message_handler(content_types=["text"])
def name(message):
    log(f"id:{message.from_user.id}; text:{message.text}")
    if str(message.from_user.id) not in ids:
        """ ФИЛЬТР ДОСТУПНЫХ ID'S """
        log(f"НОВЫЙ id:{message.from_user.id}; text:{message.text}")
        text = f'Для доступа к функционалу необходимо сообщить Ваш id администратору бота: {message.from_user.id}.'
        bot.send_message(message.chat.id, text)
    else:
        if message.text.lower() == 'галерея txt':
            # При клике на Галерею - зайти в последнюю дату в Мастерах и прочитать блокнот. Скопировать его в сообщение.
            txtFilePath = [i for i in os.listdir(getLastDatePath()) if '.txt' in i]
            if len(txtFilePath) == 0:
                bot.send_message(message.chat.id, 'Записей на сегодня нет', parse_mode='Markdown')
            else:
                theFilePath = os.path.join(getLastDatePath(), txtFilePath[0])
                with open(theFilePath, 'r', encoding="utf8") as f: lines2 = f.readlines()
                lines = [i.replace('_', '') for i in lines2]
                messageText = f"*{txtFilePath[0]} -- {len(lines)} записей*\n\n{''.join(lines)}"
                bot.send_message(message.chat.id, messageText, parse_mode='Markdown')
        elif message.text.lower() == 'галерея img':
            masters = [i for i in os.listdir(getLastDatePath()) if '.txt' not in i and '.db' not in i]
            for master in tqdm(masters):
                images = [i for i in os.listdir(os.path.join(getLastDatePath(), master)) if i != 'Thumbs.db']
                if len(images) == 0: continue
                if master == 'thumbs': continue
                if master == 'ЧД ЗАВЕРШЕНО': continue
                for image in images:
                    line2 = f'*{master.replace("_","")}*\n {image}'
                    bot.send_photo(
                        message.chat.id,
                        photo=open(os.path.join(getLastDatePath(), master, image), 'rb'),
                        caption=line2, parse_mode='Markdown')
        elif message.text == 'server reboot':
            try: os.startfile(rebootBat)
            except Exception as e:
                bot.send_message(message.chat.id, f"<code>{e}</code>", parse_mode='HTML')
                bot.send_message(message.chat.id, f"<code>Компьютер: {socket.gethostname()}</code>", parse_mode='HTML')
        elif message.text.lower() == 'даты':
            messageText = f'Перечислены доступные даты. Для просмотра содержимого необходимо кликнуть на интересующую дату.'
            getDays(message.chat.id, messageText)
        elif message.text.lower() == 'кол-вокп':
            try: messageText = f'Количество уникальных КП:\nЧД: {countKP()[0]}\nРЭП: {countKP()[1]}'
            except: messageText = 'ОШИБКА Значение временно не доступно.'
            bot.send_message(message.chat.id, messageText, parse_mode='Markdown')
        elif message.text == 'popen':
            messageText = f'Компьютер: {socket.gethostname()}\n\nЗапущены процессы:\n{startPopen()}'
            bot.send_message(message.chat.id, f"<code>{messageText}</code>", parse_mode='HTML')


@bot.callback_query_handler(func=lambda call:True)
def bd_counting(call):
    log(f"id:{call.from_user.id}; text:{call.data}")
    if str(call.from_user.id) not in ids:
        """ ФИЛЬТР ДОСТУПНЫХ ID'S """
        log(f"НОВЫЙ id:{call.from_user.id}; text:{call.data}")
        text = f'Для доступа к функционалу необходимо сообщить Ваш id администратору бота: {message.from_user.id}.'
        bot.send_message(message.chat.id, text)
    else:
        if call.data in days:
            path2date = os.path.join(path2gallery, call.data)
            masters = [i for i in os.listdir(path2date) if '.txt' not in i and '.db' not in i]
            for master in tqdm(masters):
                images = [i for i in os.listdir(os.path.join(path2date, master)) if i != 'Thumbs.db']
                if len(images) == 0: continue
                if master == 'thumbs': continue
                for image in images:
                    line2 = f'*{call.data} - {master.replace("_", "")}*\n {image}'
                    bot.send_photo(call.message.chat.id, photo=open(os.path.join(path2date, master, image), 'rb'), caption=line2, parse_mode='Markdown')


bot.polling()
