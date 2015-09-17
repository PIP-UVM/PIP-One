import numpy as np
from numpy import genfromtxt
import matplotlib.pyplot as plt

# Import for Email
import smtplib
from email.mime.text import MIMEText
from email.mime.application import MIMEApplication
from email.mime.multipart import MIMEMultipart

plt.close("all")  #close any existing plot
plt.rc('text', usetex=True) # use LaTeX fonts


# Read data file from CSV
my_data = np.genfromtxt('data/responses.csv',delimiter=',',skip_header=1)

my_name = np.genfromtxt('data/responses.csv',delimiter=',',usecols=(5), dtype=None)

ndata=len(my_data)  # number of responses

user = len(my_data) -1 # assume respondent is last entry

interest = [20,30,33,38,45,51,54,57]  # column responses to plot


# define the question labels
nquestion = 8
index = np.arange(nquestion)+1
question =[ 'work flow', 'work space', 'clinical\n services','integation\n methods', 'identification\n of need', 'patient\n engagement', 'shared\n care plans', 'follow up']




#==================================================
# Create array of user data to plot
data = []
for i in interest:
    data= np.hstack((data,my_data[user][i-1]))  
#==================================================



#==================================================
# plot the individual user responses to all queries
fig1 = plt.figure(num=1, figsize=(20, 10), dpi=80)

bar_width=0.75
bar_colors=['red','DodgerBlue','forestgreen', 'brown', 'gray','plum','cyan','DarkKhaki']
plt.bar(index, data,width=bar_width, align='center', color=bar_colors)

# plot labels and options
plt.title(my_name[1])
plt.xticks(index, question,rotation='horizontal', fontsize=12)
plt.ylabel('Score $(\%)$')
plt.ylim([0,100])
plt.gca().yaxis.grid(True)

plt.savefig('user.pdf')
#==================================================



#==================================================
# plot user responses in the context of other user responses
fig2 = plt.figure(num=2, figsize=(16, 8), dpi=80)

# create indices for all user data

index2=np.arange(ndata)+1

for i in xrange(0,nquestion):
    sortdata = np.sort(my_data[:,interest[i]-1])
    median = np.median(sortdata)

    plt.subplot(int('24'+str(i+1)))
    plt.bar(index2, sortdata,width=bar_width, align='center', color=bar_colors[i])
    plt.plot([0,ndata],[median,median],linestyle='dashed', color='k',linewidth=2)
 
    plt.xticks([])
    plt.ylim([0,100])
    plt.gca().yaxis.grid(True)
    plt.title(question[i-1], fontsize=11)
    
    tmp = sortdata.tolist()
    userindex = tmp.index(my_data[user,interest[i]-1])
    plt.bar(userindex, sortdata[userindex],width=bar_width, align='center', color='yellow') 

#
plt.savefig('allresponses.pdf')

# display the plot
plt.show()

# Import smtplib for the actual sending function

# Import the email modules we'll need

vip_email = "vip@uvm.edu"

COMMASPACE = ', '

# Create the container (outer) email message.
msg = MIMEMultipart()
msg['Subject'] = 'Your VIP Results'
# me == the sender's email address
# family = the list of all recipients' email addresses
msg['From'] = vip_email
msg['To'] = My_name
msg.preamble = 'Your VIP Results'

# Assume we know that the image files are all in PNG format
    # Open the files in binary mode.  Let the MIMEImage class automatically
    # guess the specific image type.
fp = open('allresponses.pdf', 'rb')
img = MIMEApplication(fp.read())
fp.close()

fpt = open('user.pdf', 'rb')
imgtwo = MIMEApplication(fpt.read())
fpt.close()

# print msg.items()

msg.attach(img)
msg.attach(imgtwo)

# Send the email via our own SMTP server.
s = smtplib.SMTP('localhost')
s.sendmail(me, you, msg.as_string())
s.quit()
	