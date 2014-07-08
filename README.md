
## ESE Evaluation

### 1. General
The software was created for handling the ESE evaluation in a digitalized way reduce the overhead of evaluating paper questionnaires by hand. Thus it provides functions for defining questionnaires, saving filled in questionnaires in log files, reading them back, creating and handling access keys and and provide a web interface from which all functions can be easily accessed  

### 2. Functional principle                                                      

A key file (std. *keys/keys.csv*) contains a list of keys and their state. The keys are used to authenticate a user that wants to fill in a new questionnaire.                                                               

Keys can have the following states:                                          
* **unissued**: The key was created but has not jet been issued to a student.                                                   
* **issued**: The key has been issued to a student and can be used to fill in a questionnaire.                                   
* **activated**: The key has been used to fill in a questionnaire and can now be used to get the ESE cup.                            
* **used**: The key has been used to acquire an use cup.

The people responsible for the ESE create a new list of keys by using the page *keyTable.php* and set the state of all keys that have been or will be issued to student to *issued*.If a user has filled in its questionnaire by using the access code that has been handed to him he may then go to a person that is authorized to hand out ESE cup. Said person will then log into the system by using the page *keyControlPanel.php* and check if the state of the key is set to *activated*. If that is the case he hands the student his ESE cup and sets the state of the corresponding key to *used*. The state system prevents users from filling in more than one questionnaire or acquiring more than one ESE cup.                

### 3. Preparing a new ESE Evaluation                                            

1. First a new set of keys must be created by using the *keyTable.php* page. Select the amount of keys to be high enough to cover all students and tutors.                                                                   
2. Still in *keyTable.php* set the state of all keys that will be issued to students to *issued*.                                       
3. Open the file *questionnaires/student_questionnaire.txt* and change the list of questions for students to your liking. Do the same for the tutor questions in the file *questionnaires/tutor_questionnair.txt*. You may also want to change the headline and title within the same files. 
4. If you want to keep the old log data, you may also want to change the of the log files for students and tutors in the file *libs/loggingLib.php* by changing the constants STUDENTLOGFILE and TUTORLOGFILE to the desired values. Otherwise just delete the previous log files so that blank ones will be created once the first questionnaire has been filled in.                                       

### 4. Checking if a student is authorised to acquire his cup                    

1. Access the page *keyControlPanel.php* and enter the access code that was provided to you by the people responsible for the ESE.          
2. If the code was correct you may now enter the key code of the student in question and check if its state is *activated*.                 
3. Set the state of the key to used and hand a cup to the student.           
4. You may are now back at step b) and may check a new code.                 

### 5. Analyse the results of the evaluation questionnaires                      

//TODO                                                                        

### 6. Files                                                                     

* **analysis.php**: Analysis the provided log file and displays its results.                 
* **keyControlPanel.php**: Used to access the key file and check or change the state of a key. Should be used by everyone who is authorized to hand out ESE cups.
* **keyTable.php**: Graphical presentation of the key file, can be used to generate new keys or change the state of the existing one. Should only be used by a small number of responsible people. 
* **student_questionnaire.php**: The questionnaire page for students.  
* **tutor_questionnaiere.php**: The questionnaire page for tutors.    
* **css/** Contains all css style files.         
    * **bootstrap.css**: Twitter bootstrap css file used for the styling of the pages.             
    * **style.css**: A custom style file for making a few changes to the default bootstrap style.                                
* **keys/** Contains the key files.               
    * **keys.csv**: The default key file if no other file has been specified.                   
* **libs/** Directory for all librarys.           
    * **formLib.php**: Provides useful function for creating the HTML forms that are used in the questionnaires and several other pages.       
    * **keyLib.php**: Provides functions for creating, checking and changing keys and reading and writing key files.        
    * **logging.php**: Provides functions for reading, writing and appending log files.      
    * **questionnaireLib.php**: Provides functions for reading questionnaire files.                  
* **logs/** Directory that contains all log files that have been created.               
* **questionnaires/** Directory for all questionnaire files.
    * **student_questionnaire.txt**: Questionnaire file for students.      
    * **tutor_questionnaire.txt**: Questionnaire file for tutors.
