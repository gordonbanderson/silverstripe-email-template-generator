<% include BeforeContent %>

<h1>Membership Registration Failed</h1>
<p>Dear $Member.Name,</p>
<p>You were not registered for $Event.Title as your payment was not successful.  If you think this
    is an error please contact the team on NUMBER.  Your transaction reference was $Payment.Identifier</p>

<p>Yours, </p>
<p>$ClientAdminTeamName</p>
<% include AfterContent %>

