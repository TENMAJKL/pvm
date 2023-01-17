use std::{fs, env};

#[derive(Clone, Copy)]
enum CommandKind {
    Psh,
    Pop,
    Out,
    Jmp,
    Add,
    Sub,
    Mul,
    Div,
    Edg,
    Set,
    Get,
    Iot
}

#[derive(Clone, Copy)]
struct Command {
    kind: CommandKind,
    argument: u8
}

fn parse(code: String) -> Vec<Command> {
    let mut result: Vec<Command> = Vec::new();

    for line in code.lines() {
        let command = line.split(' ').collect::<Vec<&str>>();
        
        result.push(match command[0] {
            "psh" => Command { kind: CommandKind::Psh, argument: command[1].parse::<u8>().unwrap() },
            "pop" => Command { kind: CommandKind::Pop, argument: 0 },
            "out" => Command { kind: CommandKind::Out, argument: 0 },
            "jmp" => Command { kind: CommandKind::Jmp, argument: command[1].parse::<u8>().unwrap() }, 
            "add" => Command { kind: CommandKind::Add, argument: 0 }, 
            "sub" => Command { kind: CommandKind::Sub, argument: 0 }, 
            "mul" => Command { kind: CommandKind::Mul, argument: 0 },
            "div" => Command { kind: CommandKind::Div, argument: 0 }, 
            "edg" => Command { kind: CommandKind::Edg, argument: 0 },
            "set" => Command { kind: CommandKind::Set, argument: command[1].parse::<u8>().unwrap() },
            "get" => Command { kind: CommandKind::Get, argument: command[1].parse::<u8>().unwrap() },
            "iot" => Command { kind: CommandKind::Iot, argument: 0 },
            _ => panic!("System panic: unknown command")
        });
    }

    return result;
}

fn interpret(commands: Vec<Command>) {
    let count: u8 = commands.len().try_into().unwrap();
    let mut pointer: u8 = 0;
    let mut command: Command;
    let mut stack: Vec<u8> = Vec::new();

    while pointer < count {
        command = commands[pointer as usize];
        match command.kind {
            CommandKind::Psh => { stack.push(command.argument); },
            CommandKind::Pop => { stack.pop().expect("System panic: unable to reach stack top"); },
            CommandKind::Out => { print!("{}", stack.pop().expect("System panic: unable to reach stack top") as char); },
            CommandKind::Jmp => { pointer = command.argument; continue; },
            CommandKind::Edg => { if stack.last().expect("System panic: unable to reach stack top").eq(&0) { pointer += 1; } },
            CommandKind::Add => { 
                let first = stack.pop().expect("System panic: unable to reach stack top");
                let second = stack.pop().expect("System panic: unable to reach stack top");
                stack.push(second + first);
            },
            CommandKind::Sub => { 
                let first = stack.pop().expect("System panic: unable to reach stack top");
                let second = stack.pop().expect("System panic: unable to reach stack top");
                stack.push(second - first);
            },
            CommandKind::Mul => { 
                let first = stack.pop().expect("System panic: unable to reach stack top");
                let second = stack.pop().expect("System panic: unable to reach stack top");
                stack.push(second * first);
            },
            CommandKind::Div => { 
                let first = stack.pop().expect("System panic: unable to reach stack top");
                let second = stack.pop().expect("System panic: unable to reach stack top");
                stack.push(second / first);
            },
            CommandKind::Set => {
                let value = stack.pop().expect("System panic: unable to reach stack top");
                stack[command.argument as usize] = value;
            },
            CommandKind::Get => {
                let value = stack[command.argument as usize];
                stack.push(value);
            },
            CommandKind::Iot => { print!("{}", stack.pop().expect("System panic: unable to reach stack top")); }
        };
        pointer += 1;
    }
}

fn machine(code: String) {
    let commands = parse(code);
    interpret(commands);
}

fn main() {
    let mut args = env::args();
    let filename = args.nth(1).expect("no file provided");

    let code = fs::read_to_string(filename)
        .expect("File is not readable");
    machine(code);
}
