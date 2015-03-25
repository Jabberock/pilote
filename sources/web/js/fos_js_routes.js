fos.Router.setData({"base_url":"\/pilote-git\/web\/app_dev.php","routes":{"pilote_main_loadNextNotifications":{"tokens":[["text","\/mainRequest\/loadNextNotifications"]],"defaults":[],"requirements":{"_method":"POST"},"hosttokens":[]},"pilote_main_removeAllNotifications":{"tokens":[["text","\/mainRequest\/removeAllNotifications"]],"defaults":[],"requirements":{"_method":"POST"},"hosttokens":[]},"pilote_main_removeNotification":{"tokens":[["text","\/mainRequest\/removeNotification"]],"defaults":[],"requirements":{"_method":"POST"},"hosttokens":[]},"pilote_main_notificationsRead":{"tokens":[["text","\/mainRequest\/notificationsRead"]],"defaults":[],"requirements":{"_method":"POST"},"hosttokens":[]},"pilote_profil_showAnotherProfile":{"tokens":[["variable","-","[^\/]++","id"],["text","\/profile\/id"]],"defaults":[],"requirements":[],"hosttokens":[]},"pilote_message_post":{"tokens":[["text","\/messages\/messageRequest\/post"]],"defaults":[],"requirements":{"_method":"POST"},"hosttokens":[]},"pilote_message_newThread_searchUser":{"tokens":[["text","\/messages\/newThreadSearchUser"]],"defaults":[],"requirements":[],"hosttokens":[]},"pilote_message_addParticipant_searchUser":{"tokens":[["variable","\/","\\d+","threadId"],["text","\/messages\/addParticipantSearchUser"]],"defaults":[],"requirements":{"threadId":"\\d+"},"hosttokens":[]},"pilote_tasker_board":{"tokens":[["variable","\/","\\d+","boardId"],["text","\/board"]],"defaults":[],"requirements":{"boardId":"\\d+"},"hosttokens":[]},"pilote_tasker_createTask":{"tokens":[["text","\/boardRequest\/createTask"]],"defaults":[],"requirements":{"_method":"POST"},"hosttokens":[]},"pilote_tasker_deleteTask":{"tokens":[["text","\/boardRequest\/deleteTask"]],"defaults":[],"requirements":{"_method":"POST"},"hosttokens":[]},"pilote_tasker_renameTask":{"tokens":[["text","\/boardRequest\/renameTask"]],"defaults":[],"requirements":{"_method":"POST"},"hosttokens":[]},"pilote_tasker_getTaskDetails":{"tokens":[["text","\/boardRequest\/getTaskDetails"]],"defaults":[],"requirements":{"_method":"POST"},"hosttokens":[]},"pilote_tasker_updateTaskContent":{"tokens":[["text","\/boardRequest\/updateTaskContent"]],"defaults":[],"requirements":{"_method":"POST"},"hosttokens":[]},"pilote_tasker_createTList":{"tokens":[["text","\/boardRequest\/createTList"]],"defaults":[],"requirements":{"_method":"POST"},"hosttokens":[]},"pilote_tasker_deleteTList":{"tokens":[["text","\/boardRequest\/deleteTList"]],"defaults":[],"requirements":{"_method":"POST"},"hosttokens":[]},"pilote_tasker_renameTList":{"tokens":[["text","\/boardRequest\/renameTList"]],"defaults":[],"requirements":{"_method":"POST"},"hosttokens":[]},"pilote_tasker_createStep":{"tokens":[["text","\/boardRequest\/createStep"]],"defaults":[],"requirements":{"_method":"POST"},"hosttokens":[]},"pilote_tasker_deleteStep":{"tokens":[["text","\/boardRequest\/deleteStep"]],"defaults":[],"requirements":{"_method":"POST"},"hosttokens":[]},"pilote_tasker_renameStep":{"tokens":[["text","\/boardRequest\/renameStep"]],"defaults":[],"requirements":{"_method":"POST"},"hosttokens":[]},"pilote_tasker_createDomain":{"tokens":[["text","\/boardRequest\/createDomain"]],"defaults":[],"requirements":{"_method":"POST"},"hosttokens":[]},"pilote_tasker_deleteDomain":{"tokens":[["text","\/boardRequest\/deleteDomain"]],"defaults":[],"requirements":{"_method":"POST"},"hosttokens":[]},"pilote_tasker_renameDomain":{"tokens":[["text","\/boardRequest\/renameDomain"]],"defaults":[],"requirements":{"_method":"POST"},"hosttokens":[]},"pilote_tasker_createChecklist":{"tokens":[["text","\/boardRequest\/createChecklist"]],"defaults":[],"requirements":{"_method":"POST"},"hosttokens":[]},"pilote_tasker_renameChecklist":{"tokens":[["text","\/boardRequest\/renameChecklist"]],"defaults":[],"requirements":{"_method":"POST"},"hosttokens":[]},"pilote_tasker_deleteChecklist":{"tokens":[["text","\/boardRequest\/deleteChecklist"]],"defaults":[],"requirements":{"_method":"POST"},"hosttokens":[]},"pilote_tasker_createChecklistOption":{"tokens":[["text","\/boardRequest\/createChecklistOption"]],"defaults":[],"requirements":{"_method":"POST"},"hosttokens":[]},"pilote_tasker_renameChecklistOption":{"tokens":[["text","\/boardRequest\/renameChecklistOption"]],"defaults":[],"requirements":{"_method":"POST"},"hosttokens":[]},"pilote_tasker_deleteChecklistOption":{"tokens":[["text","\/boardRequest\/deleteChecklistOption"]],"defaults":[],"requirements":{"_method":"POST"},"hosttokens":[]},"pilote_tasker_toggleChecklistOption":{"tokens":[["text","\/boardRequest\/toggleChecklistOption"]],"defaults":[],"requirements":{"_method":"POST"},"hosttokens":[]},"pilote_tasker_createComment":{"tokens":[["text","\/boardRequest\/createComment"]],"defaults":[],"requirements":{"_method":"POST"},"hosttokens":[]},"pilote_tasker_deleteComment":{"tokens":[["text","\/boardRequest\/deleteComment"]],"defaults":[],"requirements":{"_method":"POST"},"hosttokens":[]},"pilote_tasker_moveTask":{"tokens":[["text","\/boardRequest\/moveTask"]],"defaults":[],"requirements":{"_method":"POST"},"hosttokens":[]},"pilote_tasker_moveList":{"tokens":[["text","\/boardRequest\/moveList"]],"defaults":[],"requirements":{"_method":"POST"},"hosttokens":[]},"pilote_tasker_label":{"tokens":[["text","\/boardRequest\/label"]],"defaults":[],"requirements":{"_method":"POST"},"hosttokens":[]},"pilote_tasker_assign":{"tokens":[["text","\/boardRequest\/assign"]],"defaults":[],"requirements":{"_method":"POST"},"hosttokens":[]},"pilote_tasker_progress_activate":{"tokens":[["text","\/boardRequest\/activateProgress"]],"defaults":[],"requirements":{"_method":"POST"},"hosttokens":[]},"pilote_tasker_progress_update":{"tokens":[["text","\/boardRequest\/updateProgress"]],"defaults":[],"requirements":{"_method":"POST"},"hosttokens":[]},"pilote_tasker_fileUpload":{"tokens":[["text","\/boardRequest\/fileUpload"]],"defaults":[],"requirements":{"_method":"POST"},"hosttokens":[]},"pilote_tasker_fileUpload_delete":{"tokens":[["text","\/boardRequest\/deleteFile"]],"defaults":[],"requirements":{"_method":"POST"},"hosttokens":[]},"pilote_tasker_setDates":{"tokens":[["text","\/boardRequest\/setDates"]],"defaults":[],"requirements":{"_method":"POST"},"hosttokens":[]},"pilote_tasker_settings_removeUser":{"tokens":[["text","\/boardRequest\/removeUser"]],"defaults":[],"requirements":{"_method":"POST"},"hosttokens":[]},"pilote_tasker_gantt_moveTask":{"tokens":[["text","\/ganttRequest\/moveTask"]],"defaults":[],"requirements":{"_method":"POST"},"hosttokens":[]},"pilote_tasker_gantt_addLink":{"tokens":[["text","\/ganttRequest\/addLink"]],"defaults":[],"requirements":{"_method":"POST"},"hosttokens":[]},"pilote_tasker_gantt_deleteLink":{"tokens":[["text","\/ganttRequest\/deleteLink"]],"defaults":[],"requirements":{"_method":"POST"},"hosttokens":[]}},"prefix":"","host":"localhost","scheme":"http"});