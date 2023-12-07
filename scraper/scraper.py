#!C:\Users\shaun\AppData\Local\Programs\Python\Python312\python.exe
print("Content-Type: text/html\n")
import os
import urllib.parse
import sys
import canvasapi
import datetime
from pytz import timezone

def convert_due_date(assignment):
    raw_due_date = datetime.datetime.strptime(assignment.due_at,"%Y-%m-%dT%H:%M:%SZ").astimezone(timezone("America/Los_Angeles"))
    dt = datetime.timedelta(hours=8)
    return raw_due_date - dt

def filter_assignments(course):
    unfiltered = course.get_assignments(bucket="upcoming")
    filtered = []
    unfiltered = [i for i in unfiltered]
    for assignment in unfiltered:
        if datetime.datetime.now() < datetime.datetime.strptime(assignment.due_at,"%Y-%m-%dT%H:%M:%SZ"):
            filtered.append(assignment)
    return filtered

def accept_accents(string):
    return string.encode("utf-8").decode(sys.stdout.encoding)

try:
    sent_query = os.environ["QUERY_STRING"]
    query_list = sent_query.split("=")
    query_dict = urllib.parse.parse_qs(os.environ["QUERY_STRING"])

    url = query_dict["url"][0]
    try:
        auth = query_dict["auth"][0]
    except KeyError:
        auth = None

    api_data = canvasapi.Canvas(base_url=f"https://{url}.com", access_token=auth)

    assignment_dict = {}

    for course in api_data.get_courses():
        try:
            assignments = filter_assignments(course=course)
            # print(assignments)
            if assignments:
                assignment_dict[course] = assignments
                # break
        except:
            continue


    for course, assignments in assignment_dict.items():
        print(f"<h2>{course.name}</h2>")
        for assignment in assignments:
            if assignment.due_at:
                due_date = convert_due_date(assignment)
                print(f"<p>Assignment {accept_accents(assignment.name)}, due on {due_date.strftime("%m/%d/%Y - %I:%M %p")}</p>")
            else:
                print(f"<p>Assignment: {accept_accents(assignment.name)}, NO DUE DATE</p>")

# NOTE: used for deugging
except Exception as e:
    print(f"<h1>ERROR: {e}</h1>")