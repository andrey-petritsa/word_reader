#!/usr/bin/env python3
# -*- coding: UTF-8 -*-

import sys
import zipfile
import textract
import re
from textract.exceptions import ShellError

punctuation_marks = ",.!;`”’-“”?:;'\"()[]"
def generate_correct_symbols_in_word():
    correct_symbols = []
    for unicode in range(48, 58):
        correct_symbols.append(chr(unicode)) # Генерируем цифры

    for unicode in range(65, 91):
        correct_symbols.append(chr(unicode)) # Генерируем ABCD...

    for unicode in range(97, 123):
        correct_symbols.append(chr(unicode)) # Генерируем abcd...

    russian_symbols = "йцукенгшщзхъфывапролджэячсмитьбюЙЦУКЕНГШЩЗХЪФЫВАПРОЛДЖЭЯЧСМИТЬБЮ"
    for symbol in russian_symbols:
        correct_symbols.append(symbol)

    for symbol in punctuation_marks:
        correct_symbols.append(symbol)

    return correct_symbols

path_to_doc_file = sys.argv[1]

try:
    text = textract.process(path_to_doc_file)

except (zipfile.BadZipFile, ShellError) as exception: #Выбрасывает в случае, если пользователь загрузил "битый" .doc или .docx
    print(exception, end="")
    sys.exit()

text = str(text, "utf-8") #конвертируем строку в текст
words = text.split()
#Проверяем, что слово состоит только из корректных символов
correct_symbols = generate_correct_symbols_in_word()
correct_words = []
isWordValid = True
for word in words:
    isWordValid = True
    for word_symbol in word:
        if word_symbol not in correct_symbols:
            isWordValid = False
            break
    if isWordValid:
        word = re.sub(r"[,’`”“”.!;\-?:()`\"\[\]]", "", word) #Заменяем все знаки пунктуации на пустой символ
        if word != "": #Если после замены останется пустое слово, то не учитываем это слово.
            correct_words.append(word)

print(len(correct_words), end="")







