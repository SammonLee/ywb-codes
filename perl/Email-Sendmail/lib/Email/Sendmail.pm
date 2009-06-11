package Email::Sendmail;

use 5.008;
use strict;
use warnings;
use MIME::Base64;
use Encode;
use Email::Simple;
use Return::Value;
use File::Slurp qw/slurp/;
require Exporter;

our @ISA = qw(Exporter);
our %EXPORT_TAGS = ( 'all' => [ qw(
	sendmail
) ] );
our @EXPORT_OK = ( @{ $EXPORT_TAGS{'all'} } );
our @EXPORT = qw();
our $SENDMAIL = "sendmail";
our $VERSION = '0.01';
our %MAILCFG = (
    'Charset' => 'ascii',
    'MIME' => 'text/plain',
);

sub _find_sendmail {
    return $SENDMAIL if defined $SENDMAIL;

    my $sendmail;
    for my $dir (File::Spec->path) {
        if ( -x "$dir/sendmail" ) {
            $sendmail = "$dir/sendmail";
            last;
        }
    }
    return $sendmail;
}

sub _random_string {
    my $len = shift;
    my @chars = ('a'..'z', 'A'..'Z', 0..9);
    return join('', map { @chars[rand(@chars)] } 1..$len);
}

sub sendmail {
    my ($mail, @args) = @_;
    my $mailer = _find_sendmail();
    # open(my $pipe, "| cat")
    open(my $pipe, "| $mailer -t -oi @args")
        or die "Can't sendmail: $!";
    my $email = Email::Simple->new('');
    my $body;
    for ( qw/Charset MIME/ ) {
        unless ( exists $mail->{$_} ) {
            $mail->{$_} = $MAILCFG{$_};
        }
    }
    $email->header_set('Content-Type',"$mail->{MIME}; charset=$mail->{Charset}");
    if ( $mail->{Charset} eq 'ascii' ) {
        $email->header_set('Subject', $mail->{Subject});
        $body = $mail->{Body},
    } else {
        chomp(my $sub = encode_base64($mail->{Subject}));
        $email->header_set(
            'Subject',
            sprintf("=?%s?B?%s?=", $mail->{Charset}, $sub)
        );
        $email->header_set('Content-Transfer-Encoding', 'base64');
        $body = encode_base64($mail->{Body});
    }
    for ( qw/From To Cc Bcc/ ) {
        $email->header_set($_, $mail->{$_}) if exists $mail->{$_};
    }
    if ( exists $mail->{Attachment} ) {
        my $boundary = "-------" . _random_string(15);
        # my $boundary = '====' . time() . "====";
        my $oldctype = $email->header('Content-Type');
        $email->header_set(
            'Content-Type',
            qq(multipart/mixed; boundary="$boundary")
        );
        my $crlf = $email->crlf;
        my $cte = $email->header("Content-Transfer-Encoding") || '';
        $email->header_set("Content-Transfer-Encoding", '');
        $boundary = '--'.$boundary;
        $body = <<MAIL;
$boundary
Content-Type: $oldctype
Content-Transfer-Encoding: $cte$crlf$crlf$body
MAIL
        if ( !ref $mail->{Attachment} ) {
            $mail->{Attachment} = [ $mail->{Attachment} ];
        }
        for my $file ( @{$mail->{Attachment}} ) {
            my $content = encode_base64(slurp($file));
            $body .= <<MAIL;
$boundary
Content-Type: application/octet-stream; name="$file"
Content-Transfer-Encoding: base64
Content-Disposition: attachment; filename="$file"$crlf$crlf$content
MAIL
        }
        $body .= "$boundary--";
    }
    $email->body_set($body);
    print $pipe $email->as_string()
        or return failure "Error printing via pipe to $mailer: $!";
    close $pipe
        or return failure "error when closing pipe to $mailer: $!";
    return success;
}

1;
__END__

=head1 NAME

Email::Sendmail - Send email using sendmail

=head1 SYNOPSIS

  use Email::Sendmail qw/sendmail/;
  my %mail = (
      'Subject' => '你好',
      'To' => 'wenbin.ye@alibaba-inc.com',
      'From' => 'wenbin.ye@alibaba-inc.com',
      'Attachment' => 'att.txt',
      'Body' => '中国<a href="http://cn.yahoo.com">Yahoo!</a>',
      'Charset' => 'UTF-8',
      'MIME' => 'text/html',
  );
  
  sendmail(\%mail);

=head1 DESCRIPTION


=head2 EXPORT


=head1 SEE ALSO

L<Mail::Sendmail>, L<Email::Send>

=head1 AUTHOR

Wenbin Ye, E<lt>wenbin.ye@alibaba-inc.comE<gt>

=head1 COPYRIGHT AND LICENSE

Copyright (C) 2008 by Wenbin Ye

This library is free software; you can redistribute it and/or modify
it under the same terms as Perl itself, either Perl version 5.8.5 or,
at your option, any later version of Perl 5 you may have available.


=cut
