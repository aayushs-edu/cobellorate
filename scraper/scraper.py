#!C:\Users\shaun\AppData\Local\Programs\Python\Python312\python.exe
print("Content-Type: text/html\n")
import os
import urllib.parse

sent_query = os.environ["QUERY_STRING"]
query_list = sent_query.split("=")
query_dict = urllib.parse.parse_qs(os.environ["QUERY_STRING"])

def greeter(name):
    print(f"Hello {name}! This is your name!")

input_name = str(query_dict["name"])[2:-2]

print(str(greeter(input_name)))